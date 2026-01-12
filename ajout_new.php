<?php
/**
 * ajout.php - Page d'ajout de recette
 * Formulaire pour ajouter une nouvelle recette
 */

require_once 'db.php';

$error = '';
$success = '';

// Traiter l'ajout d'une recette
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $titre = trim($_POST['titre'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');

        // Validation
        if (empty($titre) || empty($ingredients) || empty($instructions) || empty($categorie)) {
            $error = "Tous les champs sont requis.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO recettes (titre, ingredients, instructions, categorie) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titre, $ingredients, $instructions, $categorie]);
            $success = "Recette ajoutée avec succès!";
            
            // Réinitialiser le formulaire
            $_POST = [];
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Recette - Gestion Recettes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">🍳 MasCuisine</a>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="recettes.php">Recettes</a></li>
                <li><a href="ajout.php" class="active">Ajouter</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="main-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="index.php">📊 Dashboard</a></li>
                    <li><a href="recettes.php">📋 Mes Recettes</a></li>
                    <li><a href="ajout.php" class="active">➕ Ajouter Recette</a></li>
                </ul>
            </aside>

            <!-- Contenu -->
            <main class="content">
                <div class="card">
                    <div class="card-header">
                        <h1>➕ Ajouter une Recette</h1>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                    <p style="text-align: center; margin-top: 1rem;">
                        <a href="recettes.php" class="btn btn-primary">Voir mes recettes</a>
                        <a href="ajout.php" class="btn btn-secondary">Ajouter une autre</a>
                    </p>
                <?php endif; ?>

                <?php if (!$success): ?>
                <div class="card">
                    <form method="POST" class="form">
                        <div class="form-group">
                            <label for="titre">Titre de la recette *</label>
                            <input type="text" id="titre" name="titre" placeholder="Ex: Pâtes à la Carbonara" 
                                   value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="categorie">Catégorie *</label>
                            <select id="categorie" name="categorie" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Entrée" <?php echo ($_POST['categorie'] ?? '') === 'Entrée' ? 'selected' : ''; ?>>🥗 Entrée</option>
                                <option value="Plat" <?php echo ($_POST['categorie'] ?? '') === 'Plat' ? 'selected' : ''; ?>>🍖 Plat</option>
                                <option value="Dessert" <?php echo ($_POST['categorie'] ?? '') === 'Dessert' ? 'selected' : ''; ?>>🍰 Dessert</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ingredients">Ingrédients * <span class="text-muted">(un par ligne)</span></label>
                            <textarea id="ingredients" name="ingredients" rows="8" 
                                      placeholder="- 500g de pâtes&#10;- 3 œufs&#10;- 200g de bacon&#10;- Sel et poivre"
                                      required><?php echo htmlspecialchars($_POST['ingredients'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instructions * <span class="text-muted">(étapes numérotées)</span></label>
                            <textarea id="instructions" name="instructions" rows="10" 
                                      placeholder="1. Cuire les pâtes&#10;2. Préparer la sauce&#10;3. Mélanger&#10;4. Servir"
                                      required><?php echo htmlspecialchars($_POST['instructions'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn btn-primary">✅ Ajouter la Recette</button>
                            <a href="recettes.php" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
