import 'package:flutter/material.dart';
import '../services/api_service.dart';

class RessourceDetailScreen extends StatefulWidget {
  const RessourceDetailScreen({super.key});

  @override
  State<RessourceDetailScreen> createState() => _RessourceDetailScreenState();
}

class _RessourceDetailScreenState extends State<RessourceDetailScreen> {
  bool _isFavori = false;
  bool _isLoggedIn = false;
  late Map<String, dynamic> ressource;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    ressource = ModalRoute.of(context)!.settings.arguments as Map<String, dynamic>;
    _checkFavori();
  }

  Future<void> _checkFavori() async {
    final token = await ApiService.getToken();
    if (token == null) return;
    setState(() => _isLoggedIn = true);

    try {
      final favoris = await ApiService.getFavoris();
      setState(() {
        _isFavori = favoris.any((f) => f['id'] == ressource['id']);
      });
    } catch (e) {}
  }

  Future<void> _toggleFavori() async {
    if (!_isLoggedIn) {
      Navigator.pushNamed(context, '/login');
      return;
    }
    if (_isFavori) {
      await ApiService.removeFavori(ressource['id']);
    } else {
      await ApiService.addFavori(ressource['id']);
    }
    setState(() => _isFavori = !_isFavori);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(ressource['titre']),
        actions: [
          IconButton(
            icon: Icon(
              _isFavori ? Icons.favorite : Icons.favorite_border,
              color: _isFavori ? Colors.red : Colors.grey,
            ),
            onPressed: _toggleFavori,
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              ressource['titre'],
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Publié le ${ressource['createdAt']}',
              style: const TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 16),
            Text(ressource['contenu']),
          ],
        ),
      ),
    );
  }
}