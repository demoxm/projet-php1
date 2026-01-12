Projet Cuisine - PHP & MySQL
============================

Description :
-------------
Ceci est une **application web de gestion de recettes de cuisine**. 
Elle permet de :
- Ajouter, modifier et supprimer des recettes
- Rechercher et filtrer les recettes
- Afficher les détails d’une recette
- Consulter des statistiques de recettes (nombre total, par catégorie)

Technologies utilisées :
------------------------
- Frontend : HTML5, Tailwind CSS, JavaScript Vanilla
- Backend : PHP (procédural)
- Base de données : MySQL
- Design : UI/UX moderne, responsive, icônes Heroicons/Font Awesome

Structure du projet :
--------------------
projet_cuisine/
│
├── index.php          : Page d'accueil / Dashboard
├── recettes.php       : Liste des recettes
├── ajout.php          : Ajouter une recette
├── modifier.php       : Modifier une recette
├── supprimer.php      : Supprimer une recette
├── /config
│   └── db.php         : Connexion à la base de données
├── /assets
│   ├── css            : Styles Tailwind et personnalisés
│   ├── js             : Scripts JavaScript
│   └── icons          : Icônes utilisées
└── README.txt         : Ce fichier explicatif

Base de données MySQL :
-----------------------
1. Créer la base de données :
   CREATE DATABASE cuisine;

2. Créer la table `recettes` :
   CREATE TABLE recettes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       titre VARCHAR(255) NOT NULL,
       ingredients TEXT NOT NULL,
       instructions TEXT NOT NULL,
       categorie VARCHAR(50) NOT NULL,
       date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

3. Exemple de quelques recettes de test :
   INSERT INTO recettes (titre, ingredients, instructions, categorie) VALUES
   ('Salade de tomates', 'Tomates, huile d\'olive, sel, herbes', 'Couper les tomates, ajouter sel et huile, mélanger.', 'Entrée'),
   ('Poulet rôti', 'Poulet, sel, poivre, herbes', 'Assaisonner le poulet et rôtir au four.', 'Plat'),
   ('Tarte aux pommes', 'Pommes, pâte brisée, sucre, cannelle', 'Étaler la pâte, ajouter les pommes, cuire au four.', 'Dessert');

Configuration :
---------------
1. Copier le projet dans le dossier XAMPP :
   C:\xampp\htdocs\projet_cuisine

2. Lancer XAMPP → démarrer **Apache** et **MySQL**

3. Vérifier le fichier `config/db.php` :
   <?php
   $host = "localhost";
   $dbname = "cuisine";
   $user = "root";  // Par défaut XAMPP
   $pass = "";      // Par défaut XAMPP

4. Accéder au projet :
   http://localhost/projet_cuisine/index.php

Fonctionnalités :
----------------
- Dashboard avec statistiques simples
- Gestion complète des recettes (CRUD)
- Recherche par titre ou ingrédient
- Filtre par catégorie
- Interface responsive et moderne

Remarques :
-----------
- Toutes les entrées utilisateurs sont validées côté serveur
- Les données sensibles sont protégées contre les injections SQL grâce à PDO
- Le projet est prêt à être amélioré avec des images et un système d’authentification si nécessaire

Auteur :
--------
Aya iharzi 
Hibatallah Hammi

