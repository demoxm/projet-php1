#!/usr/bin/env python3
"""
Script de vérification - Gestionnaire de Recettes
Vérifie que tous les fichiers importants sont présents
"""

import os
import json
from pathlib import Path

def check_file(path, name):
    """Vérifie si un fichier existe"""
    exists = os.path.exists(path)
    status = "✅" if exists else "❌"
    print(f"{status} {name}")
    return exists

def check_directory(path, name):
    """Vérifie si un répertoire existe"""
    exists = os.path.isdir(path)
    status = "✅" if exists else "❌"
    print(f"{status} {name}")
    return exists

def main():
    base_path = "."
    cuisine_path = os.path.join(base_path, "cuisine")
    
    print("🍳 VÉRIFICATION DU PROJET - Gestionnaire de Recettes")
    print("=" * 50)
    print()
    
    all_ok = True
    
    # Vérifier les répertoires
    print("📁 Répertoires:")
    all_ok &= check_directory(os.path.join(cuisine_path, "app"), "  app/")
    all_ok &= check_directory(os.path.join(cuisine_path, "database"), "  database/")
    all_ok &= check_directory(os.path.join(cuisine_path, "resources"), "  resources/")
    all_ok &= check_directory(os.path.join(cuisine_path, "public"), "  public/")
    all_ok &= check_directory(os.path.join(cuisine_path, "routes"), "  routes/")
    print()
    
    # Vérifier les modèles
    print("📦 Modèles:")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Models/Recipe.php"), "  Recipe.php")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Models/Ingredient.php"), "  Ingredient.php")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Models/Step.php"), "  Step.php")
    print()
    
    # Vérifier les contrôleurs
    print("🎮 Contrôleurs:")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Http/Controllers/RecipeController.php"), "  RecipeController.php")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Http/Controllers/RecipeIngredientController.php"), "  RecipeIngredientController.php")
    all_ok &= check_file(os.path.join(cuisine_path, "app/Http/Controllers/StepController.php"), "  StepController.php")
    print()
    
    # Vérifier les migrations
    print("🗄️  Migrations:")
    migrations_path = os.path.join(cuisine_path, "database/migrations")
    if os.path.isdir(migrations_path):
        migrations = [f for f in os.listdir(migrations_path) if f.endswith('.php')]
        print(f"  ✅ {len(migrations)} fichiers de migration trouvés")
        for migration in sorted(migrations):
            if migration.startswith('202') or migration.startswith('0001'):
                print(f"     - {migration}")
    else:
        print("  ❌ Dossier migrations non trouvé")
        all_ok = False
    print()
    
    # Vérifier les vues
    print("🎨 Vues Blade:")
    views = [
        "layout.blade.php",
        "recipes/index.blade.php",
        "recipes/create.blade.php",
        "recipes/edit.blade.php",
        "recipes/show.blade.php",
        "ingredients/add.blade.php",
        "steps/create.blade.php",
        "steps/edit.blade.php",
    ]
    for view in views:
        path = os.path.join(cuisine_path, f"resources/views/{view}")
        status = "✅" if os.path.exists(path) else "❌"
        all_ok &= os.path.exists(path)
        print(f"  {status} {view}")
    print()
    
    # Vérifier les fichiers statiques
    print("🎯 Fichiers statiques:")
    all_ok &= check_file(os.path.join(cuisine_path, "public/css/style.css"), "  public/css/style.css")
    all_ok &= check_file(os.path.join(cuisine_path, "public/js/main.js"), "  public/js/main.js")
    print()
    
    # Vérifier la configuration
    print("⚙️  Configuration:")
    all_ok &= check_file(os.path.join(cuisine_path, ".env"), "  .env")
    all_ok &= check_file(os.path.join(cuisine_path, "routes/web.php"), "  routes/web.php")
    print()
    
    # Vérifier les fichiers de documentation
    print("📖 Documentation:")
    all_ok &= check_file(os.path.join(base_path, "RESUME_LIVRAISON.txt"), "  RESUME_LIVRAISON.txt")
    all_ok &= check_file(os.path.join(base_path, "COMMANDES.txt"), "  COMMANDES.txt")
    all_ok &= check_file(os.path.join(base_path, "STRUCTURE_PROJET.txt"), "  STRUCTURE_PROJET.txt")
    all_ok &= check_file(os.path.join(cuisine_path, "DOCUMENTATION.md"), "  cuisine/DOCUMENTATION.md")
    print()
    
    # Résultat final
    print("=" * 50)
    if all_ok:
        print("✅ TOUS LES FICHIERS SONT PRÉSENTS!")
        print("\nProchaines étapes:")
        print("1. cd cuisine")
        print("2. Créer la base de données MySQL")
        print("3. php artisan migrate")
        print("4. php artisan serve")
        print("5. Ouvrir http://localhost:8000")
    else:
        print("❌ CERTAINS FICHIERS MANQUENT")
        print("Vérifiez les fichiers marqués avec ❌")
    print()

if __name__ == "__main__":
    main()
