<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
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
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier Utilisateur</h2>
        <?php
        require 'base_de_donnee.php';

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $pdo = getPDO();
            $sql = "SELECT * FROM utilisateurs WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            if ($user) {
                echo '<form action="modifier_utilisateur.php" method="post">
                        <input type="hidden" name="id" value="' . htmlspecialchars($user['id']) . '">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" value="' . htmlspecialchars($user['nom']) . '" required>
                        <label for="prenom">Prénom:</label>
                        <input type="text" id="prenom" name="prenom" value="' . htmlspecialchars($user['prenom']) . '" required>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="' . htmlspecialchars($user['email']) . '" required>
                        <label for="mot_de_passe">Nouveau Mot de Passe:</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe">
                        <label for="role">Rôle:</label>
                        <select id="role" name="role" required>
                            <option value="superadmin"' . ($user['role'] == 'superadmin' ? ' selected' : '') . '>Superadmin</option>
                            <option value="admin_utilisateur"' . ($user['role'] == 'admin_utilisateur' ? ' selected' : '') . '>Admin Utilisateur</option>
                            <option value="admin_commande"' . ($user['role'] == 'admin_commande' ? ' selected' : '') . '>Admin Commande</option>
                            <option value="admin_voiture"' . ($user['role'] == 'admin_voiture' ? ' selected' : '') . '>Admin Voiture</option>
                            <option value="utilisateur"' . ($user['role'] == 'utilisateur' ? ' selected' : '') . '>Utilisateur</option>
                        </select>
                        <input type="submit" value="Modifier">
                    </form>';
            } else {
                echo '<p>Utilisateur non trouvé.</p>';
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $mot_de_passe = $_POST['mot_de_passe'];

            $pdo = getPDO();
            if (!empty($mot_de_passe)) {
                $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, role = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $params = [$nom, $prenom, $email, $mot_de_passe_hache, $role, $id];
            } else {
                $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, role = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $params = [$nom, $prenom, $email, $role, $id];
            }

            if ($stmt->execute($params)) {
                echo '<p>Utilisateur modifié avec succès.</p>';
                echo '<p><a href="utilisateur.php">Retour à la liste des utilisateurs</a></p>';
            } else {
                echo '<p>Erreur lors de la modification de l\'utilisateur.</p>';
            }
        } else {
            echo '<p>Accès non autorisé.</p>';
        }
        ?>
    </div>
</body>
</html>