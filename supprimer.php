<?php
/**
 * supprimer.php - Suppression d'une recette
 * Supprime une recette de la base de données
 */

require_once 'db.php';

$id = (int)($_GET['id'] ?? 0);
$error = '';
$success = '';

if ($id > 0) {
    try {
        // Vérifier que la recette existe
        $stmt = $pdo->prepare("SELECT id FROM recettes WHERE id = ?");
        $stmt->execute([$id]);
        $recette = $stmt->fetch();

        if ($recette) {
            // Supprimer la recette
            $stmt = $pdo->prepare("DELETE FROM recettes WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Recette supprimée avec succès!";
        } else {
            $error = "Recette non trouvée.";
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
} else {
    $error = "ID recette invalide.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Recette</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 500px; margin: 3rem auto;">
            <div class="card-header">
                <h1><?php echo $success ? '✅ Succès' : '❌ Erreur'; ?></h1>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="recettes.php" class="btn btn-primary">Retour aux recettes</a>
                </p>
            <?php else: ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="recettes.php" class="btn btn-primary">Retour aux recettes</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
