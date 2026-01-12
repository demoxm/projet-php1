<?php
/**
 * ajout.php - Ajouter une nouvelle recette
 * Formulaire pour créer une recette avec tous les détails
 */

require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');

    // Validation
    if (empty($titre) || empty($categorie) || empty($ingredients) || empty($instructions)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!in_array($categorie, ['Entrée', 'Plat', 'Dessert'])) {
        $error = 'Catégorie invalide.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO recettes (titre, categorie, ingredients, instructions) VALUES (?, ?, ?, ?)");
            $stmt->execute([$titre, $categorie, $ingredients, $instructions]);
            $success = 'Recette ajoutée avec succès!';
            // Réinitialiser le formulaire
            $_POST = [];
        } catch (PDOException $e) {
            $error = 'Erreur lors de l\'ajout: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Recette - Gestion de Recettes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">
                🍳 MasCuisine
            </a>
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
                        <h1>➕ Ajouter une Nouvelle Recette</h1>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                        <div class="btn-group">
                            <a href="recettes.php" class="btn btn-secondary">Voir les recettes</a>
                            <a href="ajout.php" class="btn btn-primary">Ajouter une autre</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" id="addRecipeForm" onsubmit="return validateForm('addRecipeForm');">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="titre">Titre de la recette <span style="color: red;">*</span></label>
                                <input 
                                    type="text" 
                                    id="titre" 
                                    name="titre" 
                                    placeholder="Ex: Coq au Vin" 
                                    required 
                                    value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>"
                                >
                            </div>

                            <!-- Catégorie -->
                            <div class="form-group">
                                <label for="categorie">Catégorie <span style="color: red;">*</span></label>
                                <select id="categorie" name="categorie" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <option value="Entrée" <?php echo ($_POST['categorie'] ?? '') === 'Entrée' ? 'selected' : ''; ?>>🥗 Entrée</option>
                                    <option value="Plat" <?php echo ($_POST['categorie'] ?? '') === 'Plat' ? 'selected' : ''; ?>>🍖 Plat</option>
                                    <option value="Dessert" <?php echo ($_POST['categorie'] ?? '') === 'Dessert' ? 'selected' : ''; ?>>🍰 Dessert</option>
                                </select>
                            </div>

                            <!-- Ingrédients -->
                            <div class="form-group">
                                <label for="ingredients">Ingrédients <span style="color: red;">*</span></label>
                                <textarea 
                                    id="ingredients" 
                                    name="ingredients" 
                                    placeholder="Listez les ingrédients (un par ligne)" 
                                    required
                                ><?php echo htmlspecialchars($_POST['ingredients'] ?? ''); ?></textarea>
                                <small class="text-muted">Conseil: listez chaque ingrédient sur une nouvelle ligne</small>
                            </div>

                            <!-- Instructions -->
                            <div class="form-group">
                                <label for="instructions">Instructions <span style="color: red;">*</span></label>
                                <textarea 
                                    id="instructions" 
                                    name="instructions" 
                                    placeholder="Décrivez les étapes de préparation" 
                                    required
                                ><?php echo htmlspecialchars($_POST['instructions'] ?? ''); ?></textarea>
                                <small class="text-muted">Conseil: numérotez chaque étape pour plus de clarté</small>
                            </div>

                            <!-- Boutons -->
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary">✅ Ajouter la recette</button>
                                <a href="recettes.php" class="btn btn-secondary">Voir recettes</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
