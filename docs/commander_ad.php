<?php
session_start();
require 'base_de_donnee.php';

$pdo = getPDO();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les erreurs PDO
$role = $_SESSION['role'] ?? '';

if (!$role || ($role != 'superadmin' && $role != 'admin_commande')) {
    echo '<p>Accès non autorisé.</p>';
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM commandes WHERE id_commande = ?");
        if ($stmt->execute([$delete_id])) {
            echo "Commande supprimée avec succès.";
        } else {
            echo "Erreur lors de la suppression de la commande.";
        }
    } catch (PDOException $e) {
        echo "Erreur PDO : " . $e->getMessage();
    }
    header("Location: commander_ad.php"); // Redirection après suppression
    exit();
}

$sql = "SELECT 
            commandes.id_commande, 
            commandes.adresse, 
            commandes.telephone, 
            utilisateurs.nom AS utilisateur_nom, 
            utilisateurs.prenom AS utilisateur_prenom, 
            voiture.nom AS voiture_nom, 
            voiture.description AS voiture_description 
        FROM commandes
        JOIN utilisateurs ON commandes.utilisateur_id = utilisateurs.id
        JOIN voiture ON commandes.voiture_id = voiture.id";
$stmt = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Commandes</title>
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
        if ($role == 'superadmin') {
            echo '<a href="utilisateur.php">Utilisateurs</a>';
            echo '<a href="voiture.php">Voitures</a>';
            echo '<a href="commander_ad.php">Commandes</a>';
        } elseif ($role == 'admin_commande') {
            echo '<a href="commander_ad.php">Commandes</a>';
        }
        echo '<a href="connexion.php">Déconnexion</a>';
        ?>
    </div>
    <div class="content">
        <h2>Liste des Commandes</h2>

        <?php
        if ($stmt->rowCount() > 0) {
            echo "<table>
                    <tr>
                        <th>ID Commande</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Nom Utilisateur</th>
                        <th>Prénom Utilisateur</th>
                        <th>Nom Voiture</th>
                        <th>Description Voiture</th>
                    </tr>";
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id_commande"]) . "</td>
                        <td>" . htmlspecialchars($row["adresse"]) . "</td>
                        <td>" . htmlspecialchars($row["telephone"]) . "</td>
                        <td>" . htmlspecialchars($row["utilisateur_nom"]) . "</td>
                        <td>" . htmlspecialchars($row["utilisateur_prenom"]) . "</td>
                        <td>" . htmlspecialchars($row["voiture_nom"]) . "</td>
                        <td>" . htmlspecialchars($row["voiture_description"]) . "</td>
                    
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucune commande trouvée</p>";
        }
        ?>
    </div>
</body>
</html>