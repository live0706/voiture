<?php
include 'base_de_donnee.php';
session_start(); // Démarrer la session pour accéder aux variables de session

$conn = getPDO();
if (!$conn) {
    die("Erreur: La connexion à la base de données n'a pas été établie.");
}

// Supposons que le rôle de l'utilisateur soit stocké dans une variable de session
if (!isset($_SESSION['role'])) {
    die("Erreur: Rôle de l'utilisateur non défini.");
}

$role = $_SESSION['role']; // Récupérer le rôle de l'utilisateur connecté

// Ajouter une voiture
if (isset($_POST['add'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    $etat = $_POST['etat'];

    $sql = "INSERT INTO voiture (nom, description, prix, image, etat) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$nom, $description, $prix, $image, $etat]) === FALSE) {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Supprimer une voiture
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM voiture WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$id]) === FALSE) {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Modifier une voiture
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    $etat = $_POST['etat'];

    $sql = "UPDATE voiture SET nom=?, description=?, prix=?, image=?, etat=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$nom, $description, $prix, $image, $etat, $id]) === FALSE) {
        echo "Erreur: " . $stmt->errorInfo()[2];
    }
}

// Récupérer les voitures
$sql = "SELECT * FROM voiture";
$stmt = $conn->query($sql);
if ($stmt === FALSE) {
    echo "Erreur: " . $conn->errorInfo()[2];
}
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voitures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            color: #fff;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Tableau de Bord</h2>
    <?php
    if ($role) {
        if ($role == 'superadmin') {
            echo '<a href="utilisateur.php">Utilisateurs</a>';
            echo '<a href="voiture.php">Voitures</a>';
            echo '<a href="commander_ad.php">Commandes</a>';
        } elseif ($role == 'admin_voiture') {
            echo '<a href="voiture.php">Voitures</a>';
        }
        echo '<a href="connexion.php">Déconnexion</a>';
    } else {
        echo '<p>Accès non autorisé.</p>';
        exit();
    }
    ?>
</div>

<div class="content">
    <h1>Liste des Voitures</h1>
    <table>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Image</th>
            <th>En Stock</th>
            <th>Actions</th>
        </tr>
        <?php if (count($result) > 0): ?>
            <?php foreach($result as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['prix']); ?></td>
                <td><img src="data:image/png;base64,<?php echo base64_encode($row['image']); ?>" alt="<?php echo htmlspecialchars($row['nom']); ?>"></td>
                <td><?php echo htmlspecialchars($row['etat']); ?></td>
                <td>
                    <a href="voiture.php?delete=<?php echo $row['id']; ?>">Supprimer</a>
                    <a href="voiture.php?edit=<?php echo $row['id']; ?>">Modifier</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucune voiture trouvée.</td>
            </tr>
        <?php endif; ?>
    </table>

    <h2>Ajouter une Voiture</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="prix" placeholder="Prix" required>
        <input type="file" name="image" required>
        <label for="etat">En Stock:</label>
        <select name="etat" required>
            <option value="stock">stock</option>
            <option value="pris">pris</option>
        </select>
        <button type="submit" name="add">Ajouter</button>
    </form>

    <?php if (isset($_GET['edit'])): ?>
    <?php
    $id = $_GET['edit'];
    $sql = "SELECT * FROM voiture WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    ?>
    <h2>Modifier une Voiture</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>" required>
        <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" required>
        <input type="number" name="prix" value="<?php echo htmlspecialchars($row['prix']); ?>" required>
        <input type="file" name="image" required>
        <label for="etat">En Stock:</label>
        <select name="etat" required>
            <option value="stock" <?php echo $row['etat'] == 'stock' ? 'selected' : ''; ?>>stock</option>
            <option value="pris" <?php echo $row['etat'] == 'pris' ? 'selected' : ''; ?>>pris</option>
        </select>
        <button type="submit" name="update">Modifier</button>
    </form>
    <?php endif; ?>
</div>

<?php $conn = null; ?>
</body>
</html>