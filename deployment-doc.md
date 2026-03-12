# 🚀 Instructions de Configuration - Site Éducatif Laravel

## 📋 Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL/PostgreSQL
- Node.js & NPM (pour la compilation des assets)

## 🛠️ Installation Complète

### 1. Création du Projet Laravel

```bash
# Créer le projet Laravel
composer create-project laravel/laravel site-educatif
cd site-educatif

# Installer les dépendances supplémentaires
composer require laravel/sanctum
composer require intervention/image
composer require spatie/laravel-permission --dev
```

### 2. Configuration de la Base de Données

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

**Modifier le fichier `.env` :**

```env
APP_NAME="Site Éducatif"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=site_educatif
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@site-educatif.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Création des Migrations et Modèles

**Exécutez ces commandes dans l'ordre :**

```bash
# Créer toutes les structures (modèles + migrations + factories + seeders)
php artisan make:model Category -mfs
php artisan make:model Tag -mfs
php artisan make:model Article -mfs
php artisan make:model EvaluationSubject -mfs
php artisan make:model EducationalResource -mfs
php artisan make:model ForumTopic -mfs
php artisan make:model ForumReply -mfs
php artisan make:model UserLike -mfs
php artisan make:model DownloadLog -mfs

# Tables de liaison
php artisan make:migration create_article_tags_table

# Modification table users existante
php artisan make:migration add_fields_to_users_table --table=users
```

### 4. Copier les Fichiers Générés

**Remplacez le contenu des fichiers suivants par le code que j'ai généré :**

#### Migrations (`database/migrations/`)
- `xxxx_create_categories_table.php`
- `xxxx_add_fields_to_users_table.php`
- `xxxx_create_tags_table.php`
- `xxxx_create_articles_table.php`
- `xxxx_create_evaluation_subjects_table.php`
- `xxxx_create_educational_resources_table.php`
- `xxxx_create_forum_topics_table.php`
- `xxxx_create_forum_replies_table.php`
- `xxxx_create_article_tags_table.php`
- `xxxx_create_user_likes_table.php`
- `xxxx_create_download_logs_table.php`

#### Modèles (`app/Models/`)
- `User.php` (mise à jour)
- `Category.php`
- `Tag.php`
- `Article.php`
- `EvaluationSubject.php`
- `EducationalResource.php`
- `ForumTopic.php`
- `ForumReply.php`
- `UserLike.php`
- `DownloadLog.php`

#### Seeders (`database/seeders/`)
- `DatabaseSeeder.php`
- `CategorySeeder.php`
- `UserSeeder.php`
- `TagSeeder.php`

#### Factories (`database/factories/`)
- `UserFactory.php` (mise à jour)
- `ArticleFactory.php`

### 5. Exécution des Migrations et Seeders

```bash
# Créer la base de données
mysql -u root -p -e "CREATE DATABASE site_educatif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Exécuter les migrations avec les seeders
php artisan migrate:fresh --seed

# Ou séparément :
php artisan migrate
php artisan db:seed
```

### 6. Configuration du Stockage

```bash
# Créer le lien symbolique pour le stockage public
php artisan storage:link

# Créer les dossiers nécessaires
mkdir -p storage/app/public/uploads/resources
mkdir -p storage/app/public/uploads/evaluations
mkdir -p storage/app/public/uploads/avatars
mkdir -p storage/app/public/uploads/articles
```

### 7. Installation et Compilation des Assets Frontend

```bash
# Installer les dépendances Node.js
npm install

# Installer des packages supplémentaires pour l'UI
npm install bootstrap @popperjs/core
npm install alpinejs
npm install axios

# Compiler les assets
npm run dev

# Pour la production
npm run build
```

### 8. Configuration des Permissions (Optionnel)

Si vous utilisez le package spatie/laravel-permission :

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### 9. Lancer le Serveur de Développement

```bash
# Lancer le serveur Laravel
php artisan serve

# Dans un autre terminal, surveiller les changements CSS/JS
npm run dev
```

## 🔑 Comptes de Test

Après le seeding, vous pouvez utiliser ces comptes :

| Rôle | Email | Mot de passe |
|------|--------|-------------|
| Admin | admin@site-educatif.com | password123 |
| Modérateur | marie.dupont@site-educatif.com | password123 |
| Auteur | sophie.bernard@site-educatif.com | password123 |
| Membre | lucas.petit@exemple.com | password123 |

## 📂 Structure des Dossiers

```
site-educatif/
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   ├── Policies/
│   └── Services/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── storage/
│   └── app/public/uploads/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
└── public/
    └── storage/ (lien symbolique)
```

## 🔧 Commandes Artisan Utiles

```bash
# Vider tous les caches
php artisan optimize:clear

# Régénérer les fichiers de configuration
php artisan config:cache

# Voir les routes
php artisan route:list

# Créer un contrôleur avec ressources
php artisan make:controller ArticleController --resource

# Créer une policy
php artisan make:policy ArticlePolicy --model=Article

# Créer une requête de validation
php artisan make:request StoreArticleRequest

# Queue jobs (si utilisé)
php artisan queue:work

# Scheduler (à ajouter dans crontab)
php artisan schedule:run
```

## 🚨 Dépannage

### Erreurs Courantes

1. **Erreur de clé étrangère** : Vérifiez l'ordre des migrations
2. **Permission denied sur storage** :
   ```bash
   sudo chown -R www-data:www-data storage/
   sudo chmod -R 775 storage/
   ```
3. **Erreur Composer** : Vérifiez la version PHP
4. **Assets non compilés** : Exécutez `npm run dev`

### Réinitialisation Complète

```bash
# Supprimer et recréer la base
php artisan migrate:fresh --seed

# Vider les caches
php artisan optimize:clear

# Recompiler les assets
npm run dev
```

## 🌟 Prochaines Étapes

1. Créer les contrôleurs et routes
2. Développer les vues avec Blade
3. Implémenter l'authentification
4. Ajouter les fonctionnalités de upload
5. Créer l'interface d'administration
6. Tests et optimisations

## 📞 Support

Si vous rencontrez des problèmes :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Activez le mode debug : `APP_DEBUG=true`
3. Consultez la documentation Laravel : https://laravel.com/docs


php artisan db:seed --class=MailTemplateSeeder
php artisan db:seed --class=NotificationSeeder