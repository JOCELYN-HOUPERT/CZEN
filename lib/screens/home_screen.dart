import 'package:flutter/material.dart';
import '../services/api_service.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool _isLoggedIn = false;

  @override
  void initState() {
    super.initState();
    _checkLogin();
  }

  Future<void> _checkLogin() async {
    final token = await ApiService.getToken();
    setState(() {
      _isLoggedIn = token != null;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('CESIZen'),
        centerTitle: true,
        actions: [
          _isLoggedIn
              ? IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () async {
              await ApiService.deleteToken();
              _checkLogin();
            },
          )
              : IconButton(
            icon: const Icon(Icons.login),
            onPressed: () async {
              await Navigator.pushNamed(context, '/login');
              _checkLogin();
            },
          ),
        ],
      ),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Image.asset('LOGOETAUTRE/LOGO.png', height: 120),
              const SizedBox(height: 16),
              const Text(
                'Bienvenue sur CESIZen',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              const Text(
                'Votre espace bien-être numérique',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 16, color: Colors.grey),
              ),
              const SizedBox(height: 48),
              _menuButton(
                icon: Icons.article,
                label: 'Voir les ressources',
                color: const Color(0xFF4A90D9),
                onPressed: () => Navigator.pushNamed(context, '/ressources'),
              ),
              const SizedBox(height: 12),
              _menuButton(
                icon: Icons.psychology,
                label: 'Faire le diagnostic',
                color: const Color(0xFF6DBF9E),
                onPressed: () => Navigator.pushNamed(context, '/diagnostic'),
              ),
              const SizedBox(height: 12),
              if (_isLoggedIn) ...[
                _menuButton(
                  icon: Icons.favorite,
                  label: 'Mes favoris',
                  color: const Color(0xFFE57373),
                  onPressed: () async {
                    await Navigator.pushNamed(context, '/favoris');
                    _checkLogin();
                  },
                ),
                const SizedBox(height: 12),
                _menuButton(
                  icon: Icons.person,
                  label: 'Mon profil',
                  color: const Color(0xFF4A4A4A),
                  onPressed: () async {
                    await Navigator.pushNamed(context, '/profil');
                    _checkLogin();
                  },
                ),
              ] else ...[
                _menuButton(
                  icon: Icons.login,
                  label: 'Se connecter',
                  color: const Color(0xFF4A4A4A),
                  onPressed: () async {
                    await Navigator.pushNamed(context, '/login');
                    _checkLogin();
                  },
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _menuButton({
    required IconData icon,
    required String label,
    required Color color,
    required VoidCallback onPressed,
  }) {
    return SizedBox(
      width: double.infinity,
      height: 56,
      child: ElevatedButton.icon(
        onPressed: onPressed,
        icon: Icon(icon, color: Colors.white),
        label: Text(
          label,
          style: const TextStyle(fontSize: 16, color: Colors.white),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: color,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
        ),
      ),
    );
  }
}