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

try {
    // Commencer une transaction
    $db->beginTransaction();

    // Calculer la somme totale des avances pour ce nom
    $stmt = $db->prepare('SELECT SUM(somme) AS total_somme FROM AVANCES WHERE UPPER(nom) = UPPER(?)');
    $stmt->execute([$nom]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['total_somme'] !== null) {
        $totalSomme = $row['total_somme'];

        // Insérer dans la table REMBOURSEMENTS
        $stmt = $db->prepare("INSERT INTO REMBOURSEMENTS (nom, somme, admin, payement_date) VALUES (?, ?, ?, DATETIME('now', 'localtime'))");
        $stmt->execute([$nom, $totalSomme, $_SESSION['user_id']]);

        // Supprimer les avances en fonction du nom dans la table AVANCES
        $stmt = $db->prepare('DELETE FROM AVANCES WHERE UPPER(nom) = UPPER(?)');
        $result = $stmt->execute([$nom]);

        if ($result) {
            // Si tout s'est bien passé, valider la transaction
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Les avances ont été supprimées et le remboursement a été enregistré avec succès.']);
        } else {
            // Annuler la transaction en cas d'échec de la suppression
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression des avances.']);
        }
    } else {
        // Aucun enregistrement trouvé dans la table AVANCES pour ce nom
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Aucune avance trouvée pour ce nom.']);
    }

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $db->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue : ' . $e->getMessage()]);
}

