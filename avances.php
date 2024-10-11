<?php

session_start();
require "bd.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit();
}

// Fonction pour récupérer toutes les avances des clients
function getAvances() {
    try {
        $db = connectDb();
        $stmt = $db->prepare("SELECT nom, sum(somme) AS somme FROM AVANCES GROUP BY nom;");
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

// Récupérer les avances
$avances = getAvances();

// Envoyer les avances sous forme de JSON
header('Content-Type: application/json');
echo json_encode(['success' => true, 'avances' => $avances]);
