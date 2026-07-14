import 'package:flutter/material.dart';
import '../services/api_service.dart';

class DiagnosticScreen extends StatefulWidget {
  const DiagnosticScreen({super.key});

  @override
  State<DiagnosticScreen> createState() => _DiagnosticScreenState();
}

class _DiagnosticScreenState extends State<DiagnosticScreen> {
  Map<String, dynamic>? _questionnaire;
  List<int> _selectedIds = [];
  bool _isLoading = true;
  bool _isSubmitting = false;
  Map<String, dynamic>? _resultat;

  @override
  void initState() {
    super.initState();
    _loadDiagnostic();
  }

  Future<void> _loadDiagnostic() async {
    final data = await ApiService.getDiagnostic();
    setState(() {
      _questionnaire = data;
      _isLoading = false;
    });
  }

  Future<void> _submit() async {
    setState(() => _isSubmitting = true);
    final result = await ApiService.sendDiagnostic(_selectedIds);
    setState(() {
      _resultat = result;
      _isSubmitting = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) return const Scaffold(body: Center(child: CircularProgressIndicator()));

    if (_resultat != null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Résultat')),
        body: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text('Score : ${_resultat!['score']} points',
                  style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              Text('Niveau : ${_resultat!['niveau']}',
                  style: const TextStyle(fontSize: 20)),
              const SizedBox(height: 16),
              Text(_resultat!['message'], textAlign: TextAlign.center),
              const SizedBox(height: 32),
              ElevatedButton(
                onPressed: () => setState(() {
                  _resultat = null;
                  _selectedIds = [];
                }),
                child: const Text('Refaire le diagnostic'),
              ),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(title: const Text('Diagnostic de stress')),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: _questionnaire!['questions'].length,
              itemBuilder: (context, index) {
                final question = _questionnaire!['questions'][index];
                final isSelected = _selectedIds.contains(question['id']);
                return CheckboxListTile(
                  title: Text(question['texte']),
                  subtitle: Text('${question['poids']} pts'),
                  value: isSelected,
                  onChanged: (val) {
                    setState(() {
                      if (val == true) {
                        _selectedIds.add(question['id']);
                      } else {
                        _selectedIds.remove(question['id']);
                      }
                    });
                  },
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: _isSubmitting
                ? const CircularProgressIndicator()
                : ElevatedButton(
              onPressed: _submit,
              child: const Text('Calculer mon score'),
            ),
          ),
        ],
      ),
    );
  }
}