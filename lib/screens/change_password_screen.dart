import 'package:flutter/material.dart';
import '../services/api_service.dart';

class ChangePasswordScreen extends StatefulWidget {
  const ChangePasswordScreen({super.key});

  @override
  State<ChangePasswordScreen> createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends State<ChangePasswordScreen> {
  final _ancienController = TextEditingController();
  final _nouveauController = TextEditingController();
  final _confirmController = TextEditingController();
  bool _isLoading = false;
  String? _error;
  String? _success;

  Future<void> _changePassword() async {
    if (_nouveauController.text != _confirmController.text) {
      setState(() => _error = 'Les mots de passe ne correspondent pas');
      return;
    }

    setState(() {
      _isLoading = true;
      _error = null;
      _success = null;
    });

    final result = await ApiService.changePassword(
      _ancienController.text,
      _nouveauController.text,
    );

    setState(() => _isLoading = false);

    if (result.containsKey('message')) {
      setState(() => _success = 'Mot de passe modifié avec succès !');
      _ancienController.clear();
      _nouveauController.clear();
      _confirmController.clear();
    } else {
      setState(() => _error = result['error'] ?? 'Une erreur est survenue');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Changer le mot de passe')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (_error != null)
              Container(
                padding: const EdgeInsets.all(8),
                color: Colors.red.shade100,
                child: Text(_error!, style: const TextStyle(color: Colors.red)),
              ),
            if (_success != null)
              Container(
                padding: const EdgeInsets.all(8),
                color: Colors.green.shade100,
                child: Text(_success!, style: const TextStyle(color: Colors.green)),
              ),
            const SizedBox(height: 16),
            TextField(
              controller: _ancienController,
              decoration: const InputDecoration(labelText: 'Ancien mot de passe'),
              obscureText: true,
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _nouveauController,
              decoration: const InputDecoration(labelText: 'Nouveau mot de passe'),
              obscureText: true,
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _confirmController,
              decoration: const InputDecoration(labelText: 'Confirmer le nouveau mot de passe'),
              obscureText: true,
            ),
            const SizedBox(height: 24),
            _isLoading
                ? const CircularProgressIndicator()
                : SizedBox(
              width: double.infinity,
              height: 56,
              child: ElevatedButton(
                onPressed: _changePassword,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF4A90D9),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                ),
                child: const Text(
                  'Modifier le mot de passe',
                  style: TextStyle(color: Colors.white, fontSize: 16),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}