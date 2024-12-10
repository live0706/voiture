<?php
session_start();
require 'base_de_donnee.php';

$pdo = getPDO();
$role = $_SESSION['role'] ?? '';

$sql = "SELECT id, nom, prenom, email, mot_de_passe, role, date_creation FROM utilisateurs";
if ($role == 'admin_utilisateur') {
    $sql .= " WHERE role != 'superadmin'";
}
$stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
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
            margin-left: 200px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .action-links a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
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
    } elseif ($role == 'admin_utilisateur') {
        echo '<a href="utilisateur.php">Utilisateurs</a>';
    } elseif ($role == 'admin_commande') {
        echo '<a href="commande.php">Commandes</a>';
    }
    echo '<a href="connexion.php">Déconnexion</a>';
} else {
    echo '<p>Accès non autorisé.</p>';
    exit();
}
?>
    </div>
    <div class="content">
        <h2>Liste des Utilisateurs</h2>
        <a href="ajouter_utilisateur.php">Ajouter un utilisateur</a>
        <?php
        if ($stmt->rowCount() > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Mot de Passe Haché</th>
                        <th>Rôle</th>
                        <th>Date de Création</th>
                        <th>Action</th>
                    </tr>";
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["nom"]) . "</td>
                        <td>" . htmlspecialchars($row["prenom"]) . "</td>
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                        <td>" . htmlspecialchars($row["mot_de_passe"]) . "</td>
                        <td>" . htmlspecialchars($row["role"]) . "</td>
                        <td>" . htmlspecialchars($row["date_creation"]) . "</td>
                        <td class='action-links'>
                            <a href='modifier_utilisateur.php?id=" . htmlspecialchars($row["id"]) . "'>Modifier</a> |
                            <a href='supprimer_utilisateur.php?id=" . htmlspecialchars($row["id"]) . "'>Supprimer</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun utilisateur trouvé</p>";
        }
        ?>
    </div>
</body>
</html>