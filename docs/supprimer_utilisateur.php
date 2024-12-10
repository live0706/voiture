<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .message {
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Supprimer Utilisateur</h2>
        <?php
        require 'base_de_donnee.php';

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $pdo = getPDO();
            $sql = "DELETE FROM utilisateurs WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$id])) {
                echo '<div class="message success">Utilisateur supprimé avec succès.</div>';
                echo '<p><a href="utilisateur.php">Retour à la liste des utilisateurs</a></p>';
            } else {
                echo '<div class="message">Erreur lors de la suppression de l\'utilisateur.</div>';
            }
        } else {
            echo '<p>Accès non autorisé.</p>';
        }
        ?>
    </div>
</body>
</html>