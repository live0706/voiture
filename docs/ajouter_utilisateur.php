<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un Utilisateur</h2>
        <form method="post" action="ajouter_utilisateur.php">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="utilisateur">Utilisateur</option>
                <option value="admin_utilisateur">Admin Utilisateur</option>
                <option value="admin_voiture">Admin Voiture</option>
                <option value="admin_commande">Admin Commande</option>
                <option value="superadmin">Superadmin</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
<?php
require 'base_de_donnee.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $pdo = getPDO();

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, date_creation) VALUES (:nom, :prenom, :email, :password, :role, NOW())");
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $password, 'role' => $role]);

    echo "<p style='color: green;'>Utilisateur ajouté avec succès !</p>";
    echo '<p><a href="utilisateur.php">Retour à la liste des utilisateurs</a></p>';
}
?>