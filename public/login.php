<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

    <h2>Connexion</h2>

    <?php
    // Afficher les erreurs si elles existent
    if (isset($_SESSION['error_message'])) {
        echo "<p style='color:red;'>".$_SESSION['error_message']."</p>";
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="auth.php" method="POST">
        <label for="nom">Nom d'utilisateur :</label>
        <input type="text" name="nom" required><br><br>

        <label for="pass">Mot de passe :</label>
        <input type="password" name="pass" required><br><br>

        <input type="submit" value="Se connecter">
    </form>

</body>
</html>
