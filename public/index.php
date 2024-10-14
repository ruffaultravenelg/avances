<?php

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header('Location: login.php');
    exit();
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
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

    <!-- BAR DU HAUT -->
    <section class="top">
        <p>Avances par personnes</p>
        <button id="openMenu"><img src="ressources/menu.svg" alt="Ouvrir le menu"></button>
    </section>

    <!-- LISTE DES CLIENTS -->
    <section class="clients">

    </section>

    <!-- AVANCE -->
    <button id="addAvanceBtn" class="rounded-btn blue-btn add-avance"><img src="ressources/payments.svg" alt="Ajouter une avance"> Ajouter une avance</button>

    <!-- ASSOMBRISSEMENT -->
    <div id="back"></div>

    <!-- ADD AVANCE -->
    <div id="popup">

        <!-- ADD AVANCE: name -->
        <div id="popup_addavance_name">
            
            <p class="popup-title">Ajouter une avance</p>
            <p class="popup-subtitle">Nom du client</p>
            <input type="text" placeholder="Axelle Hannier" id="popup_addavance_name_value">
            <div class="popup-btns">
                <button class="rounded-btn red-btn" id="popup_addavance_name_cancel"><img src="ressources/cancel.svg" alt="Annuler"></button>
                <button class="rounded-btn green-btn" id="popup_addavance_name_continue"><img src="ressources/forward.svg" alt="Continuer"></button>
            </div>

        </div>

        <!-- ADD AVANCE: somme -->
        <div id="popup_addavance_somme">
            
            <p class="popup-title">Ajouter une avance</p>
            <p class="popup-subtitle">Somme avancé</p>
            <input type="number" min="0" placeholder="1.00" id="popup_addavance_somme_value">
            <div class="popup-btns">
                <button class="rounded-btn red-btn" id="popup_addavance_somme_cancel"><img src="ressources/cancel.svg" alt="Annuler"></button>
                <button class="rounded-btn green-btn" id="popup_addavance_somme_validate"><img src="ressources/validate.svg" alt="Valider"></button>
            </div>

        </div>

        <!-- VALIDATE AVANCE -->
        <div id="popup_validate">

            <p class="popup-title" id="popup_validate_title">X Doit Y€</p>
            <p class="popup-subtitle">Validez une fois la somme payé</p>
            <div class="popup-btns" style="margin-top: 30px;">
                <button class="rounded-btn red-btn" id="popup_validate_cancel"><img src="ressources/cancel.svg" alt="Annuler"></button>
                <button class="rounded-btn green-btn" id="popup_validate_validate"><img src="ressources/validate.svg" alt="Valider"></button>
            </div>

        </div>

    </div>

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
    <script type="module" src="scripts/app.js"></script>

</body>
</html>