<?php
/**
 * modifier.php - Page de modification de recette
 * Permet de modifier une recette existante
 */

require_once 'db.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$error = '';
$success = '';
$recette = null;

// Récupérer la recette
try {
    $stmt = $pdo->prepare("SELECT * FROM recettes WHERE id = ?");
    $stmt->execute([$id]);
    $recette = $stmt->fetch();

    if (!$recette) {
        $error = "Recette non trouvée.";
    }
} catch (PDOException $e) {
    $error = "Erreur: " . $e->getMessage();
}

// Traiter la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $recette) {
    try {
        $titre = trim($_POST['titre'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');

        if (empty($titre) || empty($ingredients) || empty($instructions) || empty($categorie)) {
            $error = "Tous les champs sont requis.";
        } else {
            $stmt = $pdo->prepare("UPDATE recettes SET titre = ?, ingredients = ?, instructions = ?, categorie = ? WHERE id = ?");
            $stmt->execute([$titre, $ingredients, $instructions, $categorie, $id]);
            $success = "Recette modifiée avec succès!";
            
            // Récupérer la recette mise à jour
            $stmt = $pdo->prepare("SELECT * FROM recettes WHERE id = ?");
            $stmt->execute([$id]);
            $recette = $stmt->fetch();
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
    <title>Modifier Recette - Gestion Recettes</title>
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
                <li><a href="ajout.php">Ajouter</a></li>
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
                    <li><a href="ajout.php">➕ Ajouter Recette</a></li>
                </ul>
            </aside>

            <!-- Contenu -->
            <main class="content">
                <div class="card">
                    <div class="card-header">
                        <h1>✏️ Modifier la Recette</h1>
                        <a href="recettes.php" class="btn btn-secondary btn-small">← Retour</a>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                    <p style="text-align: center; margin-top: 1rem;">
                        <a href="recettes.php" class="btn btn-primary">Voir mes recettes</a>
                    </p>
                <?php endif; ?>

                <?php if ($recette && !$success): ?>
                <div class="card">
                    <form method="POST" class="form">
                        <input type="hidden" name="id" value="<?php echo $recette['id']; ?>">

                        <div class="form-group">
                            <label for="titre">Titre de la recette *</label>
                            <input type="text" id="titre" name="titre" 
                                   value="<?php echo htmlspecialchars($recette['titre']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="categorie">Catégorie *</label>
                            <select id="categorie" name="categorie" required>
                                <option value="Entrée" <?php echo $recette['categorie'] === 'Entrée' ? 'selected' : ''; ?>>🥗 Entrée</option>
                                <option value="Plat" <?php echo $recette['categorie'] === 'Plat' ? 'selected' : ''; ?>>🍖 Plat</option>
                                <option value="Dessert" <?php echo $recette['categorie'] === 'Dessert' ? 'selected' : ''; ?>>🍰 Dessert</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ingredients">Ingrédients * <span class="text-muted">(un par ligne)</span></label>
                            <textarea id="ingredients" name="ingredients" rows="8" required><?php echo htmlspecialchars($recette['ingredients']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instructions * <span class="text-muted">(étapes numérotées)</span></label>
                            <textarea id="instructions" name="instructions" rows="10" required><?php echo htmlspecialchars($recette['instructions']); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn btn-primary">✅ Mettre à Jour</button>
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
