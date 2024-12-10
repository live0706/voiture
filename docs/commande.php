<?php
session_start();
require 'base_de_donnee.php';

if (!isset($_SESSION['utilisateur_id'])) {
    echo 'Vous devez être connecté pour passer une commande.';
    exit;
}

$pdo = getPDO();
$utilisateur_id = $_SESSION['utilisateur_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commander'])) {
    $voiture_id = $_POST['voiture_id'];
    echo '<style>
            form {
                max-width: 400px;
                margin: auto;
                padding: 1em;
                border: 1px solid #ccc;
                border-radius: 1em;
            }
            label {
                margin-top: 1em;
                display: block;
            }
            input {
                width: 100%;
                padding: 0.5em;
                margin-top: 0.5em;
            }
            button {
                margin-top: 1em;
                padding: 0.7em;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 0.3em;
                cursor: pointer;
            }
            button:hover {
                background-color: #0056b3;
            }
          </style>';
    echo '<form method="POST" action="commande.php">
            <input type="hidden" name="voiture_id" value="' . htmlspecialchars($voiture_id) . '">
            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" required>
            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" required>
            <button type="submit" name="valider_commande">Valider la commande</button>
          </form>';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['valider_commande'])) {
    $voiture_id = $_POST['voiture_id'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];

    $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, voiture_id, adresse, telephone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $voiture_id, $adresse, $telephone]);

    // Mettre à jour l'état de la voiture
    $stmt = $pdo->prepare("UPDATE voiture SET etat = 'pris' WHERE id = ?");
    $stmt->execute([$voiture_id]);

    echo '<p>Commande passée avec succès !</p>';
    echo '<a href="catalogue.php">Retour au catalogue</a>';
} else {
    echo '<p>Erreur lors de la commande.</p>';
}
?>