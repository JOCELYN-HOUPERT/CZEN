import 'package:flutter/material.dart';
import 'screens/home_screen.dart';
import 'screens/login_screen.dart';
import 'screens/register_screen.dart';
import 'screens/ressources_screen.dart';
import 'screens/diagnostic_screen.dart';
import 'dart:io';
import 'services/api_service.dart';
import 'screens/ressource_detail_screen.dart';
import 'screens/favoris_screen.dart';
import 'screens/profil_screen.dart';
import 'package:google_fonts/google_fonts.dart';
import 'screens/change_password_screen.dart';

void main() {
  HttpOverrides.global = DevHttpOverrides();
  runApp(const CESIZenApp());
}

class CESIZenApp extends StatelessWidget {
  const CESIZenApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'CESIZen',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF4A90D9),
        ),
        useMaterial3: true,
        textTheme: GoogleFonts.quicksandTextTheme(),
      ),
      initialRoute: '/home',
      routes: {
        '/favoris': (context) => const FavorisScreen(),
        '/profil': (context) => const ProfilScreen(),
        '/ressource-detail': (context) => const RessourceDetailScreen(),
        '/login': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/home': (context) => const HomeScreen(),
        '/ressources': (context) => const RessourcesScreen(),
        '/diagnostic': (context) => const DiagnosticScreen(),
        '/change-password': (context) => const ChangePasswordScreen(),
      },
    );
  }
}