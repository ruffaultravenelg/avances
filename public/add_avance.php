<?php
session_start();
require 'bd.php'; 

// Fonction pour renvoyer une réponse JSON avec un code HTTP
function sendResponse($success, $message, $httpCode = 200) {
    http_response_code($httpCode);
    $response = [
        'success' => $success,
        'message' => $message
    ];
    echo json_encode($response);
    exit;
}

// Vérifier si l'utilisateur est connecté (adapter selon votre logique de session)
if (!isset($_SESSION['user_id'])) {
    sendResponse(false, "Accès refusé. Vous devez être connecté.", 401);
}

// Vérifier si les données POST sont présentes
if (!isset($_POST['nom']) || !isset($_POST['somme'])) {
    sendResponse(false, "Données manquantes. Veuillez remplir le formulaire correctement.", 400);
}

$nom = htmlspecialchars(trim($_POST['nom']));
$somme = floatval($_POST['somme']);
$id = htmlspecialchars($_SESSION['user_id']);

// Vérification des données
if (empty($nom) || $somme <= 0) {
    sendResponse(false, "Le nom ne peut pas être vide et la somme doit être supérieure à 0.", 422);
}

// Add to db
try {
    // Connect
    $db = connectDb();
        
    // Préparation et exécution de la requête d'insertion
    $stmt = $db->prepare("INSERT INTO AVANCES (nom, somme, admin, creation_date) VALUES (:nom, :somme, :admin, DATETIME('now', 'localtime'))");
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':somme', $somme, PDO::PARAM_STR);
    $stmt->bindParam(':admin', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fermer la connexion
    $db = null;

    sendResponse(true, "L'avance a été ajoutée avec succès.", 200);

} catch (PDOException $e) {
    sendResponse(false, $e->getMessage(), 500);
}
