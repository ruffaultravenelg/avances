<?php
// Inclusion de la fonction de connexion à la base de données
require_once 'bd.php';

// Fonction pour créer un nouvel utilisateur
function createUser($nom, $pass) {
    // Connexion à la base de données
    $db = connectDb();
    
    // Hashage du mot de passe pour des raisons de sécurité
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
    
    // Préparation de la requête SQL pour insérer un nouvel utilisateur
    $query = "INSERT INTO USERS (nom, pass) VALUES (:nom, :pass)";
    
    try {
        // Exécution de la requête avec des valeurs sécurisées
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':pass', $hashedPass);
        $stmt->execute();
        
        echo "Utilisateur créé avec succès !";
    } catch (PDOException $e) {
        echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
    }
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    $pass = trim($_POST['pass']);
    
    // Validation basique des champs
    if (!empty($nom) && !empty($pass)) {
        // Appel de la fonction pour créer un utilisateur
        createUser($nom, $pass);
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'utilisateur</title>
</head>
<body>
    <h1>Créer un nouvel utilisateur</h1>
    
    <form action="create_user.php" method="post">
        <label for="nom">Nom d'utilisateur :</label>
        <input type="text" name="nom" id="nom" required>
        
        <label for="pass">Mot de passe :</label>
        <input type="password" name="pass" id="pass" required>
        
        <button type="submit">Créer</button>
    </form>
</body>
</html>
