# AlloVirtuel

> Application PHP pour la gestion de contenus multilingues et d'images avec panneau d'administration.

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![Status](https://img.shields.io/badge/Status-Actif-green)](https://github.com)

---

## 📖 Table des matières
- [Aperçu](#aperçu)
- [Prérequis](#prérequis)
- [Installation](#installation-rapide)
- [Configuration](#-configuration--utilisation)
- [Structure](#-structure-du-projet)
- [Sécurité](#️-sécurité--bonnes-pratiques)
- [Déploiement](#déploiement)
- [Contribution](#-contribuer)
- [License](#license)

---

## 🎯 Aperçu

Application PHP simple pour la gestion de contenus et d'images avec un panneau d'administration. Le contenu principal est stocké dans des fichiers JSON (`allovirtuelContent_fr.json`, `allovirtuelContent_en.json`) et les images sont dans les dossiers `images/` et `uploads/`.

**Fonctionnalités clés :**
- 🌍 Contenu multilingue (FR/EN)
- 📸 Gestion d'images (upload, suppression, renommage)
- 🔐 Panneau d'administration sécurisé
- 📝 Éditeur de fichiers intégré
- 🗂️ Gestionnaire de fichiers backend

---

## ✅ Prérequis
- **OS** : Windows, Linux, macOS
- **PHP** : 7.4+ (avec extensions PDO/MySQL)
- **Base de données** : MySQL 5.7+ ou MariaDB 10.3+
- **Serveur** : Apache (XAMPP, WAMP, LAMP, etc.)
- **Navigateur** : Chrome, Firefox, Safari, Edge (récents)

---

## 🚀 Installation rapide

### Sur votre machine locale (XAMPP/Windows)
1. Cloner le dépôt :
   ```bash
   git clone https://github.com/YOUR_USERNAME/allovirtuel.git
   cd allovirtuel/allo
   ```

2. Placer le dossier dans `C:\xampp\htdocs\` :
   ```
   C:\xampp\htdocs\allo
   ```

3. Démarrer Apache et MySQL via XAMPP.

4. Configurer la base de données :
   - Ouvrir `http://localhost/phpmyadmin`
   - Créer une nouvelle base de données `allo_db`
   - Importer le fichier : `admin/database.sql`

5. Configurer le fichier `admin/config.php` :
   ```php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'allo_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ?>
   ```

6. Vérifier les permissions :
   - Le dossier `uploads/` doit être accessible en écriture par PHP
   - `chmod 755 uploads/` sur Linux/macOS

7. Accéder à l'application :
   - Front public : `http://localhost/allo/index.php`
   - Admin : `http://localhost/allo/admin/index.php` (login requis)

### Via Docker (optionnel)
```bash
docker-compose up -d
```
*(À ajouter si un docker-compose.yml existe)*

---

## 🔧 Configuration & utilisation

### Pages principales
| Page | URL | Description |
|------|-----|-------------|
| Accueil public | `index.php` | Page d'accueil publique |
| Admin - Connexion | `admin/logIn.php` | Formulaire de login |
| Admin - Dashboard | `admin/index.php` | Panneau d'administration |
| Admin - Déconnexion | `admin/logout.php` | Quitter la session |

### Fonctionnalités du panneau admin
- **Upload d'images** → `admin/upload.php`
- **Suppression d'images** → `admin/delete_image.php`
- **Renommage d'images** → `admin/rename_image.php`
- **Gestion de fichiers** → `admin/file_manager.php`
- **Éditeur de fichiers** → `admin/file_editor.php`
- **Récupération des images** → `admin/get_images.php` (API JSON)
- **Mise à jour du contenu** → `admin/update_json.php`

### Contenu multilingue
Le contenu affiché est stocké dans les fichiers JSON :
- `allovirtuelContent_fr.json` — Contenu français
- `allovirtuelContent_en.json` — Contenu anglais

---

## 📁 Structure du projet

```
allo/
├── index.php                      # Page d'accueil publique
├── test.php                       # Script de test
├── README.md                      # Ce fichier
├── allovirtuelContent_fr.json     # Contenu français
├── allovirtuelContent_en.json     # Contenu anglais
│
├── admin/                         # Panneau d'administration
│   ├── index.php                  # Dashboard admin
│   ├── logIn.php                  # Formulaire de connexion
│   ├── logout.php                 # Déconnexion
│   ├── config.php                 # Configuration (DB, constantes)
│   ├── database.sql               # Schéma SQL initial
│   ├── check_file.php             # Vérification de fichiers
│   ├── upload.php                 # Upload d'images
│   ├── delete_image.php           # Suppression d'images
│   ├── rename_image.php           # Renommage d'images
│   ├── get_images.php             # API - Récupération images (JSON)
│   ├── file_manager.php           # Gestionnaire de fichiers
│   ├── file_editor.php            # Éditeur de fichiers
│   └── update_json.php            # Mise à jour du contenu JSON
│
├── images/                        # Images publiques statiques
├── uploads/                       # Images uploadées par les utilisateurs
│
└── prototypes/                    # Pages prototypes HTML
    ├── Car.html
    ├── Real_estate.html
    ├── Restaurant.html
    ├── patt.html
    ├── spa.html
    └── traiteur.html
```

---

## ⚠️ Sécurité & bonnes pratiques

### ✋ À faire IMMÉDIATEMENT après installation
- [ ] Changer les identifiants d'administration (login/password)
- [ ] Changer la clé secrète/JWT si utilisée
- [ ] Mettre à jour `admin/config.php` avec des identifiants forts
- [ ] Modifier les permissions du dossier `admin/` (restreindre l'accès)

### 🔒 Recommandations de sécurité

#### Protéger le dossier `admin/`
**Via `.htaccess` (Apache)** :
```apache
<Directory /path/to/admin>
    AuthType Basic
    AuthName "Admin Access"
    AuthUserFile /path/to/.htpasswd
    Require valid-user
</Directory>
```

#### Validation des uploads
- Vérifier les types MIME (whitelist : jpg, png, gif)
- Limiter la taille maximale des fichiers
- Éviter l'exécution de scripts dans `uploads/`

```apache
# Dans uploads/.htaccess
<FilesMatch "\.(php|phtml|php3|php4|php5|phps|pht)$">
    Deny from all
</FilesMatch>
```

#### Base de données
- Ne JAMAIS utiliser `root` en production
- Créer un utilisateur MySQL avec permissions limitées :
  ```sql
  CREATE USER 'allo_user'@'localhost' IDENTIFIED BY 'strong_password';
  GRANT SELECT, INSERT, UPDATE, DELETE ON allo_db.* TO 'allo_user'@'localhost';
  ```

#### Logging & monitoring
- Désactiver l'affichage des erreurs en production
- Activer le logging côté serveur (`error_log`)
- Surveiller les fichiers uploadés pour des activités suspectes
- Implémenter un système de logs d'audit pour les actions admin

#### Autres mesures
- Utiliser HTTPS en production
- Implémenter un rate limiting (protection contre brute force)
- Valider et nettoyer toutes les entrées utilisateur (XSS, SQL injection)
- Mettre à jour PHP, MySQL et les dépendances régulièrement

---

## Déploiement

### Préparation pour la production

1. **Configurer la base de données en production** :
   - Créer une base de données sur le serveur
   - Importer `admin/database.sql`
   - Mettre à jour `admin/config.php`

2. **Sécuriser l'environnement** :
   - Générer un mot de passe fort pour le compte admin
   - Configurer les permissions du serveur
   - Activer HTTPS
   - Mettre en place le `.htaccess` pour protéger `admin/`

3. **Optimisations** :
   - Compresser les images avant upload
   - Mettre en cache les fichiers JSON
   - Activer la compression Gzip dans Apache
   - Mettre à jour la configuration PHP (`memory_limit`, `upload_max_filesize`)

4. **Vérifications finales** :
   - Tester tous les formulaires et uploads
   - Vérifier les logs d'erreur
   - Tester la récupération de données JSON
   - Vérifier les permissions sur `uploads/`

### Mise à jour depuis GitHub

```bash
# Récupérer les dernières modifications
git pull origin main

# Importer les changements de schéma si nécessaire
# (voir git log ou les releases pour les migrations)
```

---

## 🤝 Contribuer

Les contributions sont bienvenues ! Voici comment participer :

### Étapes pour contribuer
1. **Fork** le projet
   ```bash
   git clone https://github.com/YOUR_USERNAME/allovirtuel.git
   cd allovirtuel/allo
   ```

2. **Créer une branche** pour votre fonctionnalité
   ```bash
   git checkout -b feature/ma-fonctionnalite
   # ou pour une correction
   git checkout -b fix/mon-bugfix
   ```

3. **Commiter vos changements** avec des messages clairs
   ```bash
   git add .
   git commit -m "Ajout de [fonctionnalité] - description courte"
   ```

4. **Pusher vers GitHub**
   ```bash
   git push origin feature/ma-fonctionnalite
   ```

5. **Ouvrir une Pull Request** (PR) avec :
   - Une description claire de vos changements
   - Des captures d'écran si applicable
   - Référence à un issue si existant
   - Tests effectués

### Bonnes pratiques
- ✅ Tester en local avant de pusher
- ✅ Respecter le style de code existant (indentation, nommage)
- ✅ Ajouter des commentaires pour du code complexe
- ✅ Documenter les nouvelles fonctionnalités
- ✅ Une PR = une fonctionnalité ou un bugfix
- ❌ Ne pas modifier `admin/database.sql` sans coordination
- ❌ Ne pas commiter les fichiers de configuration locaux

---

## 📄 License

Ce projet est sous licence **MIT**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## ✉️ Contact & Support

- 📧 **Email** : [votre-email@example.com]
- 🐛 **Signaler un bug** : [Issues](https://github.com/YOUR_USERNAME/allovirtuel/issues)
- 💡 **Demander une fonctionnalité** : [Discussions](https://github.com/YOUR_USERNAME/allovirtuel/discussions)

---

## 📚 Ressources utiles

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Apache `.htaccess` Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [XAMPP Documentation](https://www.apachefriends.org/)

---

**Merci d'utiliser AlloVirtuel!** ⭐

Si le projet vous a plu, n'hésitez pas à le **⭐ Star** sur GitHub pour montrer votre soutien ! 😊
