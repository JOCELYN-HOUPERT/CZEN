import 'package:flutter/material.dart';
import '../services/api_service.dart';

class RessourcesScreen extends StatefulWidget {
  const RessourcesScreen({super.key});

  @override
  State<RessourcesScreen> createState() => _RessourcesScreenState();
}

class _RessourcesScreenState extends State<RessourcesScreen> {
  List<dynamic> _ressources = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadRessources();
  }

  Future<void> _loadRessources() async {
    final ressources = await ApiService.getRessources();
    setState(() {
      _ressources = ressources;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ressources')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
        itemCount: _ressources.length,
        itemBuilder: (context, index) {
          final ressource = _ressources[index];
          return Card(
            margin: const EdgeInsets.all(8),
            child: ListTile(
              title: Text(ressource['titre']),
              subtitle: Text(
                ressource['contenu'].length > 100
                    ? '${ressource['contenu'].substring(0, 100)}...'
                    : ressource['contenu'],
              ),
              trailing: const Icon(Icons.arrow_forward_ios),
              onTap: () {
                Navigator.pushNamed(
                  context,
                  '/ressource-detail',
                  arguments: ressource,
                );
              },
            ),
          );
        },
      ),
    );
  }
}