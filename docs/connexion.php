
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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
        input[type="email"], input[type="password"] {
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
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php
        session_start();
        require 'base_de_donnee.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $pdo = getPDO();

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['utilisateur_id'] = $user['id']; // Stocker l'ID de l'utilisateur dans la session
                $_SESSION['role'] = $user['role'];
                switch ($user['role']) {
                    case 'superadmin':
                        header("Location: tableau_de_bord.php");
                        break;
                    case 'admin_commande':
                    case 'admin_voiture':
                    case 'admin_utilisateur':
                        header("Location: tableau_de_bord.php");
                        break;
                    case 'utilisateur':
                        header("Location: accueil.html");
                        break;
                    default:
                        echo "<p style='color: red;'>Rôle non reconnu.</p>";
                        break;
                }
                exit();
            } else {
                echo "<p style='color: red;'>Email ou mot de passe incorrect.</p>";
            }
        }
        ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <div class="link">
            <p>Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a></p>
        </div>
    </div>
</body>
</html>
