\# CESIZen — Plateforme de bien-être mental



!\[Symfony](https://img.shields.io/badge/Symfony-8.0-black)

!\[Flutter](https://img.shields.io/badge/Flutter-3.x-blue)

!\[PHP](https://img.shields.io/badge/PHP-8.4-purple)

!\[Tests](https://github.com/JOCELYN-HOUPERT/CZEN/actions/workflows/tests.yml/badge.svg)



\## 📋 Présentation



CESIZen est une application de santé mentale développée pour le Ministère de la Santé et de la Prévention. Elle permet aux citoyens de diagnostiquer leur niveau de stress, de consulter des ressources pédagogiques et de suivre leur bien-être au quotidien.



\### Acteurs

\- \*\*Visiteur anonyme\*\* — consultation des ressources et diagnostic de stress

\- \*\*Utilisateur connecté\*\* — favoris, historique des diagnostics, profil

\- \*\*Administrateur\*\* — gestion du contenu, des utilisateurs et statistiques



\---



\## 🛠️ Stack technique



| Composant | Technologie |

|---|---|

| Back-end | Symfony 8 (PHP 8.4) |

| Base de données | MySQL 8.0 (Docker) |

| API | REST + JWT (LexikJWT) |

| Back-office | EasyAdmin 5 |

| Front-end mobile | Flutter 3.x (Dart) |

| Tests | PHPUnit 13 |

| CI/CD | GitHub Actions |



\---



\## 🚀 Installation



\### Prérequis

\- PHP 8.4+

\- Composer 2.x

\- Symfony CLI 5.x

\- Docker Desktop

\- Flutter SDK 3.x



\### Back-end Symfony



```bash

\# Cloner le projet

git clone https://github.com/JOCELYN-HOUPERT/CZEN.git

cd CZEN



\# Installer les dépendances

composer install



\# Lancer Docker

docker compose up -d



\# Générer les clés JWT

docker run --rm -v ${PWD}/config/jwt:/jwt alpine/openssl genrsa -out /jwt/private.pem 4096

docker run --rm -v ${PWD}/config/jwt:/jwt alpine/openssl rsa -in /jwt/private.pem -pubout -out /jwt/public.pem



\# Créer la base de données

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load



\# Lancer le serveur

symfony serve

```



\### Front-end Flutter



```bash

\# Changer de branche

git checkout flutter



\# Installer les dépendances

flutter pub get



\# Lancer l'application

flutter run -d windows

```



\---



\## 📱 Fonctionnalités



\### Module Diagnostic Holmes \& Rahe

\- 43 événements de vie avec pondération LCU

\- Calcul automatique du score de vulnérabilité

\- 3 niveaux : Faible (< 150) / Modéré (150-300) / Élevé (> 300)

\- Sauvegarde des résultats pour les utilisateurs connectés



\### Module Ressources

\- Bibliothèque d'articles sur la santé mentale

\- Système de favoris pour les utilisateurs connectés

\- Gestion complète par l'administrateur



\### Gestion des comptes

\- Inscription / Connexion / Déconnexion

\- Changement de mot de passe

\- Suppression de compte (droit à l'oubli RGPD)

\- Désactivation de compte par l'administrateur



\### Back-office Admin

\- Dashboard avec statistiques anonymisées

\- Graphique de distribution des niveaux de stress

\- CRUD complet sur toutes les entités



\---



\## 🔒 Sécurité \& RGPD



\- Mots de passe hashés avec bcrypt

\- Authentification API via JWT

\- HTTPS obligatoire

\- Droit à l'oubli implémenté

\- Données anonymisées dans les statistiques

\- Suppression en cascade des données utilisateur



\---



\## 🧪 Tests



```bash

\# Lancer les tests de non-régression

php bin/phpunit

```



\*\*5 tests automatisés :\*\*

\- Inscription d'un nouvel utilisateur

\- Connexion et génération du token JWT

\- Accès public aux ressources

\- Calcul du score de diagnostic

\- Unicité de l'email à l'inscription



\---



\## 🌿 Branches Git



| Branche | Description |

|---|---|

| `main` | Code stable — production |

| `develop` | Développement en cours |

| `flutter` | Application mobile Flutter |



\---



\## 📁 Structure du projet

