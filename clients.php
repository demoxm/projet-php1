<?php
/**
 * clients.php - Gestion des clients
 * CRUD complet des clients
 */

require_once 'config/database.php';

$error = '';
$success = '';

// Traiter l'ajout d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $cin = trim($_POST['cin'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($nom) || empty($telephone) || empty($cin)) {
            $error = "Les champs nom, téléphone et CIN sont requis.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO clients (nom, telephone, cin, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $telephone, $cin, $email]);
            $success = "Client ajouté avec succès!";
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Traiter la modification d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    try {
        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $cin = trim($_POST['cin'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($id && $nom && $telephone && $cin) {
            $stmt = $pdo->prepare("UPDATE clients SET nom = ?, telephone = ?, cin = ?, email = ? WHERE id = ?");
            $stmt->execute([$nom, $telephone, $cin, $email, $id]);
            $success = "Client modifié avec succès!";
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Traiter la suppression d'un client
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Client supprimé avec succès!";
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Récupérer tous les clients
try {
    $stmt = $pdo->query("SELECT * FROM clients ORDER BY nom ASC");
    $clients = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Erreur: " . $e->getMessage();
    $clients = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Clients - Gestion Location</title>
    <link rel="stylesheet" href="assets/css/tailwind.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">🚗 CarRental</a>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="voitures.php">Voitures</a></li>
                <li><a href="clients.php" class="active">Clients</a></li>
                <li><a href="locations.php">Locations</a></li>
                <li><a href="retour.php">Retours</a></li>
            </ul>
        </div>
    </nav>

    <!-- Conteneur principal -->
    <div class="container">
        <div class="main-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="index.php">📊 Dashboard</a></li>
                    <li><a href="voitures.php">🚙 Gestion Voitures</a></li>
                    <li><a href="clients.php" class="active">👥 Gestion Clients</a></li>
                    <li><a href="locations.php">📅 Locations</a></li>
                    <li><a href="retour.php">↩️ Retours</a></li>
                </ul>
            </aside>

            <!-- Contenu principal -->
            <main class="content">
                <div class="card">
                    <div class="card-header">
                        <h1>👥 Gestion des Clients</h1>
                        <button class="btn btn-primary" onclick="openModal('addClientModal')">+ Ajouter un client</button>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <!-- Tableau des clients -->
                <div class="card">
                    <?php if (!empty($clients)): ?>
                        <table class="table" id="clientsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>CIN</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?php echo $client['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($client['nom']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($client['cin']); ?></td>
                                        <td><?php echo htmlspecialchars($client['telephone']); ?></td>
                                        <td><?php echo htmlspecialchars($client['email'] ?? '-'); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-secondary btn-small" onclick="editClient(<?php echo $client['id']; ?>, '<?php echo htmlspecialchars($client['nom']); ?>', '<?php echo htmlspecialchars($client['telephone']); ?>', '<?php echo htmlspecialchars($client['cin']); ?>', '<?php echo htmlspecialchars($client['email'] ?? ''); ?>')">
                                                    ✏️ Modifier
                                                </button>
                                                <a href="clients.php?delete=<?php echo $client['id']; ?>" class="btn btn-danger btn-small" onclick="return confirmDelete('Supprimer ce client?')">
                                                    🗑️ Supprimer
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted text-center mt-3">Aucun client. <a href="#" onclick="openModal('addClientModal')">Ajouter un</a></p>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal - Ajouter client -->
    <div class="modal" id="addClientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter un Client</h2>
                <button class="modal-close" onclick="closeModal('addClientModal')">×</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="nom" required>
                </div>
                <div class="form-group">
                    <label>CIN *</label>
                    <input type="text" name="cin" required>
                </div>
                <div class="form-group">
                    <label>Téléphone *</label>
                    <input type="tel" name="telephone" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Modal - Modifier client -->
    <div class="modal" id="editClientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier le Client</h2>
                <button class="modal-close" onclick="closeModal('editClientModal')">×</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="nom" id="editNom" required>
                </div>
                <div class="form-group">
                    <label>CIN *</label>
                    <input type="text" name="cin" id="editCin" required>
                </div>
                <div class="form-group">
                    <label>Téléphone *</label>
                    <input type="tel" name="telephone" id="editTelephone" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="editEmail">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script>
        function editClient(id, nom, telephone, cin, email) {
            document.getElementById('editId').value = id;
            document.getElementById('editNom').value = nom;
            document.getElementById('editTelephone').value = telephone;
            document.getElementById('editCin').value = cin;
            document.getElementById('editEmail').value = email;
            openModal('editClientModal');
        }
    </script>
</body>
</html>
