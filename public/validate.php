<?php

session_start();
require "bd.php";  // Utilise la fonction de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit();
}

// Vérifier si le nom est fourni dans la requête POST
if (!isset($_POST['nom'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nom manquant.']);
    exit();
}

$nom = htmlspecialchars(trim($_POST['nom']));  // Échapper le nom pour éviter les injections SQL

// Vérifier si le nom est valide (non vide)
if (empty($nom)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Le nom ne peut pas être vide.']);
    exit();
}

// Connexion à la base de données
$db = connectDb();

// Supprimer l'avance en fonction du nom dans la table AVANCES
$stmt = $db->prepare('DELETE FROM AVANCES WHERE UPPER(nom) = UPPER(?)');
$result = $stmt->execute([$nom]);

if ($result) {
    // Si la suppression a réussi
    echo json_encode(['success' => true, 'message' => 'Les avances ont été supprimée avec succès.']);
} else {
    // Si la suppression a échoué
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la suppression des avances.']);
}
