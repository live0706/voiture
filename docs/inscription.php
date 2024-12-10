<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
        input[type="text"], input[type="password"], input[type="email"] {
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
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #007BFF;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = 'connexion.php';
            }, 2000); // Redirection après 3 secondes
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <?php
        require 'base_de_donnee.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $pdo = getPDO();

            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                echo "<p style='color: red;'>Email déjà utilisé.</p>";
            } else {
                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :password, 'utilisateur')");
                $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'password' => $password]);
                echo "<p style='color: green;'>Inscription réussie !</p>";
                echo "<script>redirectToLogin();</script>";
            }
        }
        ?>
        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>
        <div class="link">
            <p>Déjà un compte ? <a href="connexion.php">Connectez-vous</a></p>
        </div>
    </div>
</body>
</html>