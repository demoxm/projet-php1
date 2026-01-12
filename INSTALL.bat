@echo off
REM ===============================================
REM Installation rapide - Gestionnaire de Recettes
REM Pour Windows avec XAMPP
REM ===============================================

echo.
echo 🍳 Installation du Gestionnaire de Recettes
echo ===========================================
echo.

REM Aller au répertoire du projet
cd cuisine

REM 1. Installer les dépendances PHP
echo 1️⃣  Installation des dépendances Composer...
call composer install

REM 2. Générer la clé d'application
echo.
echo 2️⃣  Génération de la clé d'application...
call php artisan key:generate

REM 3. Exécuter les migrations
echo.
echo 3️⃣  Création de la base de données...
echo.
echo ⚠️  IMPORTANT: Assurez-vous que MySQL est démarré dans XAMPP!
echo.
call php artisan migrate

REM 4. Instructions finales
echo.
echo ✅ Installation complétée!
echo.
echo Prochaines étapes:
echo 1. Lancez le serveur: php artisan serve
echo 2. Ouvrez: http://localhost:8000 dans votre navigateur
echo.
echo Pour ajouter des données de test:
echo mysql -u root gestionnaire_recettes ^< database/seeders/recettes_test.sql
echo.
pause
