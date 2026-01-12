<?php
/**
 * index.php - Dashboard Application Gestion de Recettes
 * Affiche les statistiques et les recettes récentes
 */

require_once 'db.php';

// Récupérer les statistiques
try {
    // Total recettes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM recettes");
    $total_recettes = $stmt->fetch()['total'];

    // Recettes par catégorie
    $stmt = $pdo->query("SELECT categorie, COUNT(*) as count FROM recettes GROUP BY categorie");
    $stats_categorie = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Recettes récentes
    $stmt = $pdo->query("SELECT * FROM recettes ORDER BY date_creation DESC LIMIT 5");
    $recettes_recentes = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion de Recettes</title>
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
                <li><a href="index.php" class="active">Dashboard</a></li>
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
                    <li><a href="index.php" class="active">📊 Dashboard</a></li>
                    <li><a href="recettes.php">📋 Mes Recettes</a></li>
                    <li><a href="ajout.php">➕ Ajouter Recette</a></li>
                </ul>
            </aside>

            <!-- Contenu -->
            <main class="content">
                <div class="card">
                    <div class="card-header">
                        <h1>🍳 Dashboard - Recettes</h1>
                        <p class="text-muted"><?php echo date('d/m/Y H:i'); ?></p>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <!-- Statistiques -->
                <div class="stats-grid">
                    <div class="stat-card" style="border-left-color: #3b82f6;">
                        <h3>📊 Total Recettes</h3>
                        <div class="number"><?php echo $total_recettes; ?></div>
                    </div>

                    <div class="stat-card" style="border-left-color: #10b981;">
                        <h3>🥗 Entrées</h3>
                        <div class="number" style="color: #10b981;">
                            <?php echo $stats_categorie['Entrée'] ?? 0; ?>
                        </div>
                    </div>

                    <div class="stat-card" style="border-left-color: #f59e0b;">
                        <h3>🍖 Plats</h3>
                        <div class="number" style="color: #f59e0b;">
                            <?php echo $stats_categorie['Plat'] ?? 0; ?>
                        </div>
                    </div>

                    <div class="stat-card" style="border-left-color: #ec4899;">
                        <h3>🍰 Desserts</h3>
                        <div class="number" style="color: #ec4899;">
                            <?php echo $stats_categorie['Dessert'] ?? 0; ?>
                        </div>
                    </div>
                </div>

                <!-- Recettes récentes -->
                <div class="card">
                    <div class="card-header">
                        <h2>📋 Recettes Récentes</h2>
                        <a href="recettes.php" class="btn btn-primary btn-small">Voir toutes</a>
                    </div>

                    <?php if (!empty($recettes_recentes)): ?>
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
                                <?php foreach ($recettes_recentes as $recette): ?>
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
                                                <a href="recettes.php?id=<?php echo $recette['id']; ?>" class="btn btn-secondary btn-small">
                                                    👁️ Voir
                                                </a>
                                                <a href="modifier.php?id=<?php echo $recette['id']; ?>" class="btn btn-secondary btn-small">
                                                    ✏️ Modifier
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted text-center mt-3">Aucune recette. <a href="ajout.php">Ajouter une</a></p>
                    <?php endif; ?>
                </div>

                <!-- Bienvenue -->
                <div class="card">
                    <div class="card-header">
                        <h2>ℹ️ Bienvenue</h2>
                    </div>
                    <p>Gérez vos recettes préférées facilement:</p>
                    <ul style="margin-left: 1.5rem; margin-top: 1rem;">
                        <li>✓ <strong>Ajouter</strong> vos recettes personnelles</li>
                        <li>✓ <strong>Organiser</strong> par catégories (Entrée, Plat, Dessert)</li>
                        <li>✓ <strong>Consulter</strong> ingrédients et instructions</li>
                        <li>✓ <strong>Modifier</strong> ou <strong>supprimer</strong> au besoin</li>
                    </ul>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
