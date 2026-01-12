#!/bin/bash
# ===============================================
# Script d'installation rapide - Gestionnaire de Recettes
# ===============================================
# Usage: bash install.sh (sur Linux/Mac) ou cmd sur Windows

echo "🍳 Installation du Gestionnaire de Recettes"
echo "==========================================="

# Aller au répertoire du projet
cd cuisine

# 1. Installer les dépendances PHP
echo "1️⃣  Installation des dépendances Composer..."
composer install

# 2. Générer la clé d'application
echo "2️⃣  Génération de la clé d'application..."
php artisan key:generate

# 3. Exécuter les migrations
echo "3️⃣  Création de la base de données..."
php artisan migrate

# 4. Instructions finales
echo ""
echo "✅ Installation complétée!"
echo ""
echo "Prochaines étapes:"
echo "1. Assurez-vous que MySQL est en cours d'exécution (XAMPP)"
echo "2. La base de données 'gestionnaire_recettes' doit être créée"
echo "3. Lancez le serveur: php artisan serve"
echo "4. Ouvrez: http://localhost:8000"
echo ""
echo "Pour ajouter des données de test:"
echo "mysql -u root gestionnaire_recettes < database/seeders/recettes_test.sql"
