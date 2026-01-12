<?php
/**
 * recettes.php - Liste et gestion des recettes
 * Affiche toutes les recettes avec recherche et filtres
 */

require_once 'db.php';

$search = trim($_GET['search'] ?? '');
$categorie = trim($_GET['categorie'] ?? '');
$afficher_id = (int)($_GET['id'] ?? 0);

try {
    // Récupérer toutes les recettes
    $query = "SELECT * FROM recettes WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND (titre LIKE ? OR ingredients LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
    }

    if (!empty($categorie)) {
        $query .= " AND categorie = ?";
        $params[] = $categorie;
    }

    $query .= " ORDER BY date_creation DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $recettes = $stmt->fetchAll();

    // Récupérer détails si visualisation simple recette
    $recette_detail = null;
    if ($afficher_id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM recettes WHERE id = ?");
        $stmt->execute([$afficher_id]);
        $recette_detail = $stmt->fetch();
    }

} catch (PDOException $e) {
    $error = "Erreur: " . $e->getMessage();
    $recettes = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes - Gestion Recettes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">🍳 MasCuisine</a>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="recettes.php" class="active">Recettes</a></li>
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
                    <li><a href="recettes.php" class="active">📋 Mes Recettes</a></li>
                    <li><a href="ajout.php">➕ Ajouter Recette</a></li>
                </ul>
            </aside>

            <!-- Contenu -->
            <main class="content">
                <!-- Détails recette si sélectionnée -->
                <?php if ($recette_detail): ?>
                    <div class="card">
                        <div class="card-header">
                            <h1><?php echo htmlspecialchars($recette_detail['titre']); ?></h1>
                            <a href="recettes.php" class="btn btn-secondary btn-small">← Retour</a>
                        </div>
                        
                        <p>
                            <span class="badge badge-<?php echo strtolower($recette_detail['categorie']); ?>">
                                <?php 
                                    $icones = ['Entrée' => '🥗', 'Plat' => '🍖', 'Dessert' => '🍰'];
                                    echo ($icones[$recette_detail['categorie']] ?? '') . ' ' . htmlspecialchars($recette_detail['categorie']);
                                ?>
                            </span>
                        </p>

                        <h2>📝 Ingrédients</h2>
                        <div class="recipe-content">
                            <?php echo nl2br(htmlspecialchars($recette_detail['ingredients'])); ?>
                        </div>

                        <h2 style="margin-top: 2rem;">👨‍🍳 Instructions</h2>
                        <div class="recipe-content">
                            <?php echo nl2br(htmlspecialchars($recette_detail['instructions'])); ?>
                        </div>

                        <p style="margin-top: 2rem; color: #999; font-size: 0.9rem;">
                            Créée le <?php echo date('d/m/Y à H:i', strtotime($recette_detail['date_creation'])); ?>
                        </p>

                        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                            <a href="modifier.php?id=<?php echo $recette_detail['id']; ?>" class="btn btn-secondary">✏️ Modifier</a>
                            <a href="supprimer.php?id=<?php echo $recette_detail['id']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette recette?')">🗑️ Supprimer</a>
                            <a href="recettes.php" class="btn btn-primary">← Retour</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Liste des recettes -->
                    <div class="card">
                        <div class="card-header">
                            <h1>📋 Mes Recettes</h1>
                            <a href="ajout.php" class="btn btn-primary">+ Ajouter</a>
                        </div>
                    </div>

                    <!-- Recherche et Filtres -->
                    <div class="card">
                        <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <input type="text" name="search" placeholder="🔍 Chercher par titre ou ingrédient..." 
                                   value="<?php echo htmlspecialchars($search); ?>" 
                                   style="flex: 1; min-width: 200px;">
                            
                            <select name="categorie" style="min-width: 150px;">
                                <option value="">Toutes les catégories</option>
                                <option value="Entrée" <?php echo $categorie === 'Entrée' ? 'selected' : ''; ?>>🥗 Entrée</option>
                                <option value="Plat" <?php echo $categorie === 'Plat' ? 'selected' : ''; ?>>🍖 Plat</option>
                                <option value="Dessert" <?php echo $categorie === 'Dessert' ? 'selected' : ''; ?>>🍰 Dessert</option>
                            </select>
                            
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <?php if (!empty($search) || !empty($categorie)): ?>
                                <a href="recettes.php" class="btn btn-secondary">Réinitialiser</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- Tableau recettes -->
                    <div class="card">
                        <?php if (!empty($recettes)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recettes as $recette): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($recette['titre']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($recette['categorie']); ?>">
                                                    <?php echo htmlspecialchars($recette['categorie']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($recette['date_creation'])); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="recettes.php?id=<?php echo $recette['id']; ?>" class="btn btn-secondary btn-small">👁️ Voir</a>
                                                    <a href="modifier.php?id=<?php echo $recette['id']; ?>" class="btn btn-secondary btn-small">✏️ Modifier</a>
                                                    <a href="supprimer.php?id=<?php echo $recette['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Supprimer cette recette?')">🗑️ Supprimer</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted text-center mt-3">
                                Aucune recette trouvée. 
                                <?php if (!empty($search) || !empty($categorie)): ?>
                                    <a href="recettes.php">Réinitialiser les filtres</a>
                                <?php else: ?>
                                    <a href="ajout.php">Ajouter une recette</a>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
