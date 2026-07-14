import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class DevHttpOverrides extends HttpOverrides {
  @override
  HttpClient createHttpClient(SecurityContext? context) {
    return super.createHttpClient(context)
      ..badCertificateCallback = (X509Certificate cert, String host, int port) => true;
  }
}

class ApiService {
  static const String baseUrl = 'https://127.0.0.1:8000/api';
  static const FlutterSecureStorage _storage = FlutterSecureStorage();

  static Future<void> saveToken(String token) async {
    await _storage.write(key: 'jwt_token', value: token);
  }

  static Future<String?> getToken() async {
    return await _storage.read(key: 'jwt_token');
  }

  static Future<void> deleteToken() async {
    await _storage.delete(key: 'jwt_token');
  }

  static Future<Map<String, String>> _authHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  static Future<Map<String, dynamic>> register(String nom, String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'nom': nom, 'email': email, 'password': password}),
    );
    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('https://127.0.0.1:8000/api/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'username': email, 'password': password}),
    );
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      await saveToken(data['token']);
      return {'success': true};
    }
    final data = jsonDecode(response.body);
    return {'success': false, 'message': data['message'] ?? 'Email ou mot de passe incorrect'};
  }

  static Future<List<dynamic>> getRessources() async {
    final response = await http.get(Uri.parse('$baseUrl/ressources'));
    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> getRessource(int id) async {
    final response = await http.get(Uri.parse('$baseUrl/ressources/$id'));
    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> getDiagnostic() async {
    final response = await http.get(Uri.parse('$baseUrl/diagnostic'));
    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> sendDiagnostic(List<int> questions) async {
    final token = await getToken();

    final headers = token != null
        ? await _authHeaders()
        : {'Content-Type': 'application/json'};

    final response = await http.post(
      Uri.parse('$baseUrl/diagnostic/resultat'),
      headers: headers,
      body: jsonEncode({'questions': questions}),
    );
    return jsonDecode(response.body);
  }

  static Future<List<dynamic>> getFavoris() async {
    final headers = await _authHeaders();
    final response = await http.get(
      Uri.parse('$baseUrl/favoris'),
      headers: headers,
    );
    if (response.statusCode == 401) {
      throw Exception('Token expiré');
    }
    return jsonDecode(response.body);
  }

  static Future<void> addFavori(int id) async {
    final headers = await _authHeaders();
    await http.post(Uri.parse('$baseUrl/favoris/$id'), headers: headers);
  }

  static Future<void> removeFavori(int id) async {
    final headers = await _authHeaders();
    await http.delete(Uri.parse('$baseUrl/favoris/$id'), headers: headers);
  }

  static Future<Map<String, dynamic>> getProfil() async {
    final headers = await _authHeaders();
    final response = await http.get(Uri.parse('$baseUrl/profil'), headers: headers);
    return jsonDecode(response.body);
  }

  static Future<Map<String, dynamic>> changePassword(
      String ancienPassword, String nouveauPassword) async {
    final headers = await _authHeaders();
    final response = await http.put(
      Uri.parse('$baseUrl/profil/password'),
      headers: headers,
      body: jsonEncode({
        'ancien_password': ancienPassword,
        'nouveau_password': nouveauPassword,
      }),
    );
    return jsonDecode(response.body);
  }

  static Future<void> deleteProfil() async {
    final headers = await _authHeaders();
    await http.delete(Uri.parse('$baseUrl/profil'), headers: headers);
  }


}