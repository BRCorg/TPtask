<?php
session_start();
include "config/database.php";
include "repository/usersRepository.php";
include "repository/tasksRepository.php";

if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    try {
        // Commence une transaction pour s'assurer que tout est supprimé
        $pdo = getConnexion();
        $pdo->beginTransaction();
        
        // Supprime d'abord toutes les tâches de l'utilisateur
        $query = $pdo->prepare("DELETE FROM task WHERE id_user = ?");
        $query->execute([$userId]);
        
        // Puis supprime l'utilisateur
        $query = $pdo->prepare("DELETE FROM user WHERE id = ?");
        $query->execute([$userId]);
        
        // Valide toutes les suppressions
        $pdo->commit();
        
        // Détruit la session
        session_destroy();
        
        // Redirige vers la page d'accueil
        header('Location: index.php');
        exit();
        
    } catch (PDOException $e) {
        // En cas d'erreur, annule toutes les modifications
        $pdo->rollBack();
        die("Erreur lors de la suppression du compte : " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit();
} 