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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Tableau de Bord</h2>
        <?php
        session_start();
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];
            if ($role == 'superadmin' || $role == 'admin_utilisateur') {
                echo '<a href="utilisateur.php">Utilisateurs</a>';
            }
            if ($role == 'superadmin' || $role == 'admin_voiture') {
                echo '<a href="voiture.php">Voitures</a>';
            }
            if ($role == 'superadmin' || $role == 'admin_commande') {
                echo '<a href="commander_ad.php">Commandes</a>';
            }
            echo '<a href="connexion.php">Déconnexion</a>';
        } else {
            echo '<p>Accès non autorisé.</p>';
        }
        ?>
    </div>
    <div class="content">
        <h2>Gestion et administration du site (Espace réservé aux administrateurs)</h2>
    </div>
</body>
</html>