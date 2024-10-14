<?php

require "bd.php";
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header('Location: login.php');
    exit();
}

// Fonction pour récupérer tout les remboursements des clients
function getRemboursements() {
    try {
        $db = connectDb();
        $stmt = $db->prepare("SELECT nom, somme, payement_date, admin FROM REMBOURSEMENTS ORDER BY payement_date DESC;");
        $stmt->execute();

        // Récupérer les résultats sous forme de tableau associatif
        $avances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fermer la connexion
        $db = null;
        return $avances;
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des avances : " . $e->getMessage();
        return [];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADIIL - Avances</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/remboursements.css">
</head>
<body>

    <!-- BAR DU HAUT -->
    <section class="top">
        <p>Avances validées</p>
        <button id="openMenu"><img src="ressources/menu.svg" alt="Ouvrir le menu"></button>
    </section>

    <!-- LISTE DES REMBOURSEMENTS -->
    <section class="remboursements">

        <?php foreach(getRemboursements() as $row): ?>

            <div class="remboursement">
                <div>
                    <p><?= $row['nom'] ?></p>
                    <?php
                        // Convertir la date en objet DateTime
                        $date = new DateTime($row['payement_date']);
                        // Formater la date en "d/m/Y à H\hi"
                        $formattedDate = $date->format('d/m/Y \à H\hi');
                    ?>
                    <p>le <?= $formattedDate ?> par <?= $row['admin'] ?></p>
                </div>
                <p><?= $row['somme'] ?>€</p>
            </div>

        <?php endforeach; ?>

    </section>

    <!-- MENU -->
    <nav id="menu">
        <div class="btns">
            <button class="rounded-btn blue-btn" onclick="window.location.href='index.php';"><img src="ressources/payments.svg" alt="Avances">Avances</button>
            <button class="rounded-btn blue-btn" onclick="window.location.href='remboursements.php';"><img src="ressources/folder.svg" alt="Folder">Avances validées</button>
            <button class="rounded-btn blue-btn"><img src="ressources/search.svg" alt="Search">Rechercher</button>
            <button class="rounded-btn blue-btn" onclick="window.location.href='logout.php';"><img src="ressources/logout.svg" alt="Logout">Se déconnecter</button>
        </div>
        <button class="rounded-btn blue-btn" id="closeMenu"><img src="ressources/cancel.svg" alt="Search">Fermer</button>
    </nav>

    <!-- SCRIPTS JS -->
    <script type="module" src="scripts/nav.js"></script>

</body>
</html>