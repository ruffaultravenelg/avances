<?php
session_start();
require 'bd.php';  // Utilise la fonction de connexion à la base de données

// Vérifier que les données de connexion sont présentes
if (!isset($_POST['nom']) || !isset($_POST['pass'])) {
    $_SESSION['error_message'] = 'Nom d\'utilisateur et mot de passe requis.';
    header('Location: login.php');
    exit();
}

$nom = htmlspecialchars(trim($_POST['nom']));
$pass = $_POST['pass'];  // Ne pas utiliser htmlspecialchars sur le mot de passe

// Connexion à la base de données
$db = connectDb();

// Vérification du nom d'utilisateur dans la base de données
$stmt = $db->prepare('SELECT * FROM USERS WHERE nom = ?;');
$stmt->execute([$nom]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe et si le mot de passe est correct
if ($user && password_verify($pass, $user['pass'])) {
    // L'utilisateur est authentifié
    $_SESSION['user_id'] = $user['id'];
    header('Location: index.php');
    exit();
} else {
    // Si les identifiants sont incorrects
    $_SESSION['error_message'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
    header('Location: login.php');
    exit();
}
