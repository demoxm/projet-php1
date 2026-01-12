-- ===================================
-- Setup de la base de données Recettes
-- ===================================

-- Créer la table des recettes
CREATE TABLE IF NOT EXISTS recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    ingredients LONGTEXT NOT NULL,
    instructions LONGTEXT NOT NULL,
    categorie ENUM('Entrée', 'Plat', 'Dessert') NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- Données d'exemple
-- ===================================

INSERT INTO recettes (titre, ingredients, instructions, categorie) VALUES
(
    'Salade Niçoise',
    '- 200g de laitue\n- 2 tomates\n- 1 oignon rouge\n- 4 œufs\n- 100g de thon en conserve\n- 50g d''olives noires\n- Vinaigrette',
    '1. Laver et découper la laitue\n2. Couper les tomates en quartiers\n3. Émincer l''oignon rouge\n4. Cuire les œufs 8 minutes\n5. Assembler tous les ingrédients\n6. Verser la vinaigrette',
    'Entrée'
),
(
    'Pâtes à la Carbonara',
    '- 400g de pâtes\n- 200g de lard ou bacon\n- 4 œufs\n- 100g de fromage Pecorino\n- Poivre\n- Sel',
    '1. Cuire l''eau et le sel à feu vif\n2. Faire cuire les pâtes\n3. Dorer le lard dans une poêle\n4. Battre les œufs avec le fromage râpé\n5. Égoutter les pâtes\n6. Mélanger chaud avec la préparation\n7. Assaisonner de poivre',
    'Plat'
),
(
    'Coq au Vin',
    '- 1 poulet de 1,5 kg\n- 1 bouteille de vin rouge\n- 300g de champignons\n- 200g de petits oignons\n- 150g de lardons\n- 2 carottes\n- 2 gousses d''ail\n- Laurier et thym',
    '1. Découper le poulet\n2. Dorer les lardons et le poulet\n3. Ajouter les oignons et carottes\n4. Verser le vin rouge\n5. Ajouter les champignons et herbes\n6. Laisser cuire 1h30 à feu doux\n7. Assaisonner et servir chaud',
    'Plat'
),
(
    'Tiramisu',
    '- 6 œufs\n- 250g de mascarpone\n- 100g de sucre\n- 300ml de café froid\n- 30ml de rhum ou Marsala\n- 250g de biscuits à la cuillère\n- Cacao en poudre',
    '1. Séparer les blancs et jaunes d''œufs\n2. Fouetter les jaunes avec le sucre\n3. Incorporer le mascarpone\n4. Monter les blancs en neige\n5. Incorporer délicatement les blancs\n6. Tremper les biscuits dans le café\n7. Alterner couches de biscuits et crème\n8. Saupoudrer de cacao\n9. Réfrigérer 4h minimum',
    'Dessert'
),
(
    'Crème Brûlée',
    '- 500ml de crème liquide\n- 1 gousse de vanille\n- 5 jaunes d''œufs\n- 100g de sucre\n- Sucre pour caraméliser',
    '1. Chauffer la crème avec la vanille\n2. Fouetter les jaunes avec le sucre\n3. Verser lentement la crème chaude\n4. Tamiser la préparation\n5. Verser dans les ramequins\n6. Cuire au bain-marie 40 minutes\n7. Refroidir au réfrigérateur\n8. Caraméliser le sucre à la surface avec un chalumeau',
    'Dessert'
),
(
    'Soupe à l''Oignon Gratinée',
    '- 1 kg d''oignons\n- 1 litre de bouillon de bœuf\n- 200g de pain grillé\n- 200g de fromage Gruyère\n- 50g de beurre\n- Vin blanc\n- Sel et poivre',
    '1. Émincer finement les oignons\n2. Fondre au beurre pendant 30 minutes\n3. Déglacer au vin blanc\n4. Ajouter le bouillon\n5. Laisser mijoter 45 minutes\n6. Assaisonner\n7. Servir dans des bols\n8. Couvrir de pain grillé et fromage\n9. Gratiner au four',
    'Entrée'
);
