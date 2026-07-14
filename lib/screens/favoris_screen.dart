import 'package:flutter/material.dart';
import '../services/api_service.dart';

class FavorisScreen extends StatefulWidget {
  const FavorisScreen({super.key});

  @override
  State<FavorisScreen> createState() => _FavorisScreenState();
}

class _FavorisScreenState extends State<FavorisScreen> {
  List<dynamic> _favoris = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadFavoris();
  }

  Future<void> _loadFavoris() async {
    try {
      final favoris = await ApiService.getFavoris();
      setState(() {
        _favoris = favoris;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        Navigator.pushReplacementNamed(context, '/login');
      }
    }
  }

  Future<void> _removeFavori(int id) async {
    await ApiService.removeFavori(id);
    _loadFavoris();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mes Favoris')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _favoris.isEmpty
          ? const Center(child: Text('Aucun favori pour le moment'))
          : ListView.builder(
        itemCount: _favoris.length,
        itemBuilder: (context, index) {
          final ressource = _favoris[index];
          return Card(
            margin: const EdgeInsets.all(8),
            child: ListTile(
              title: Text(ressource['titre']),
              subtitle: Text(ressource['contenu'].length > 100
                  ? '${ressource['contenu'].substring(0, 100)}...'
                  : ressource['contenu']),
              trailing: IconButton(
                icon: const Icon(Icons.favorite, color: Colors.red),
                onPressed: () => _removeFavori(ressource['id']),
              ),
            ),
          );
        },
      ),
    );
  }
}
