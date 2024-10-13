<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>

    <div class="login">
       <h2>Connexion</h2>

        <?php
        // Afficher les erreurs si elles existent
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color:red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="auth.php" method="POST">
            <p for="nom">Nom d'utilisateur</p>
            <input type="text" name="nom" required>

            <p for="pass">Mot de passe</p>
            <input type="password" name="pass" required>

            <button type="submit" class="rounded-btn blue-btn"><img src="ressources/login.svg" alt="Login">Se connecter</button>
        </form>
    </div>

</body>
</html>
