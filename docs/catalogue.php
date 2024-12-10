<?php
include 'base_de_donnee.php';
session_start(); // Démarrer la session pour accéder aux variables de session

$conn = getPDO();
if (!$conn) {
    die("Erreur: La connexion à la base de données n'a pas été établie.");
}

// Supposons que le rôle de l'utilisateur soit stocké dans une variable de session
if (!isset($_SESSION['role'])) {
    die("Erreur: Rôle de l'utilisateur non défini.");
}

$role = $_SESSION['role']; // Récupérer le rôle de l'utilisateur connecté

// Récupérer les voitures en stock
$sql = "SELECT * FROM voiture WHERE etat = 'stock'";
$stmt = $conn->query($sql);
if ($stmt === FALSE) {
    echo "Erreur: " . $conn->errorInfo()[2];
}
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voitures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            margin: 0;
            padding: 0;
        }
        
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .container h1 {
            color: white;
            text-align: center; /* Centrer le texte */
        }
        .showcase {
            min-height: 400px;
            background: url('showcase.jpg') no-repeat 0 -400px;
            text-align: center;
            color: #fff;
        }
        .showcase h1 {
            margin-top: 100px;
            font-size: 55px;
            margin-bottom: 10px;
        }
        .showcase p {
            font-size: 20px;
        }
        .cars {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .car {
            background: #fff;
            margin: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 30%;
            border-radius: 10px;
            text-align: center;
        }
        .car img {
            max-width: 100%;
            border-radius: 10px;
        }
        .car h3 {
            margin: 10px 0;
        }
        .car p {
            color: #777;
        }
        .car button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .car button:hover {
            background: #555;
        }
    </style>
</head>
<body>

<div id="menu-container"></div> <!-- Conteneur pour le menu -->

<div class="container">
    <h1>Liste des Voitures en Stock</h1>
    <div class="cars">
        <?php if (count($result) > 0): ?>
            <?php foreach($result as $row): ?>
            <div class="car">
                <img src="data:image/png;base64,<?php echo base64_encode($row['image']); ?>" alt="<?php echo htmlspecialchars($row['nom']); ?>">
                <h3><?php echo htmlspecialchars($row['nom']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Prix: </strong><?php echo htmlspecialchars($row['prix']); ?> €</p>
                <form method="POST" action="commande.php">
                    <input type="hidden" name="voiture_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="commander">Commander</button>
                </form>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune voiture en stock trouvée.</p>
        <?php endif; ?>
    </div>
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