<?php
session_start();
require 'base_de_donnee.php';

$pdo = getPDO();
$utilisateur_id = $_SESSION['utilisateur_id'];

$stmt = $pdo->prepare("SELECT commandes.*, commandes.adresse, voiture.description, utilisateurs.nom, commandes.telephone 
                       FROM commandes 
                       JOIN voiture ON commandes.voiture_id = voiture.id 
                       JOIN utilisateurs ON commandes.utilisateur_id = utilisateurs.id 
                       WHERE commandes.utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:black;
        }

        .container {
            margin-left: 260px;
            padding: 20px;
        }

        .commande {
            border: 1px solid #ccc;
            padding: 1em;
            margin: 1em 0;
            border-radius: 1em;
            background-color: white;
        }

        h1 {
            text-align: center;
            color: white;
        }

        .commande p {
            margin: 0.5em 0;
            color:black;
        }

        .commande strong {
            color: black;
        }
        .test {
            color:white;
        }
    </style>
</head>
<body>
    <div id="menu-container"></div> <!-- Conteneur pour le menu -->

    <div class="container">
        <h1>Vos Commandes</h1>
        <?php if (count($commandes) > 0): ?>
            <?php foreach ($commandes as $commande): ?>
                <div class="commande">
                    <p><strong>Voiture :</strong> <?php echo htmlspecialchars($commande['description']); ?></p>
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($commande['nom']); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($commande['adresse']); ?></p>
                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($commande['telephone']); ?></p>
                    <p><strong>Date :</strong> <?php echo isset($commande['date_commande']) ? htmlspecialchars($commande['date_commande']) : 'Non disponible'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class='test'>Vous n'avez pas encore passé une commande.Veillez à bien faire le clic sur le bouton 'commander'.</p>
        <?php endif; ?>
    </div>

    <script>
       // Charger le menu
    fetch('menu.html')
      .then(response => response.text())
      .then(data => {
        document.getElementById('menu-container').innerHTML = data;

        // Ajouter le bouton de menu après le chargement du menu
        const menu = document.querySelector('.menu');
        const toggleButton = document.createElement('button');
        toggleButton.innerText = '☰ Menu';
        toggleButton.classList.add('menu-toggle');
        document.body.appendChild(toggleButton);

        // Affichage du menu au clic sur le bouton
        toggleButton.addEventListener('click', function () {
          menu.classList.toggle('active');
        });
      })
      .catch(error => console.error('Error loading menu:', error));

    function loadPage(page) {
      fetch(page)
        .then(response => response.text())
        .then(data => {
          document.querySelector('.main-content').innerHTML = data;
        })
        .catch(error => console.error('Error loading page:', error));
    }
    </script>
</body>
</html>