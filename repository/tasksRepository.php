<?php

function createTask(int $userId, string $title, string $description, bool $urgent, bool $important) {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("INSERT INTO task (id_user, title, description, statut, urgent, important) 
                           VALUES (?, ?, ?, 0, ?, ?)");
    
    $query->execute([$userId, $title, $description, $urgent ? 1 : 0, $important ? 1 : 0]);
}

function getTasksByUserId(int $userId) {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("SELECT * FROM task 
        WHERE id_user = ? 
        ORDER BY 
        CASE 
            WHEN urgent = 1 AND important = 1 THEN 1
            WHEN urgent = 1 THEN 2
            WHEN important = 1 THEN 3
            ELSE 4
        END, 
        id DESC");
    
    $query->execute([$userId]);
    
    return $query->fetchAll();
}

function updateTask(int $taskId, string $title, string $description, bool $urgent, bool $important, bool $completed = false) {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("UPDATE task 
                           SET title = ?, description = ?, urgent = ?, important = ?, statut = ? 
                           WHERE id = ?");
    
    $query->execute([$title, $description, $urgent ? 1 : 0, $important ? 1 : 0, $completed ? 1 : 0, $taskId]);
}

function deleteTask(int $taskId, int $userId) {
    $pdo = getConnexion();
    
    // On vérifie que la tâche appartient bien à l'utilisateur
    $query = $pdo->prepare("DELETE FROM task WHERE id = ? AND id_user = ?");
    
    $query->execute([$taskId, $userId]);
}

function getTaskById(int $taskId, int $userId) {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("SELECT * FROM task WHERE id = ? AND id_user = ?");
    
    $query->execute([$taskId, $userId]);
    
    return $query->fetch();
}

function getUserStats(int $userId) {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN statut = 1 THEN 1 END) AS task_count,
            SUM(
                CASE
                    WHEN statut = 1 AND urgent = 1 AND important = 1 THEN 5
                    WHEN statut = 1 AND urgent = 1 THEN 3
                    WHEN statut = 1 AND important = 1 THEN 1
                    ELSE 0
                END
            ) AS total_points
        FROM task
        WHERE id_user = ?
    ");
    
    $query->execute([$userId]);
    $result = $query->fetch();
    
    // Si pas de points, initialiser à 0
    $stats = [
        'task_count' => $result['task_count'] ?? 0,
        'total_points' => $result['total_points'] ?? 0,
        'badge' => 'Débutant'
    ];
    
    // Définir le badge
    if ($stats['task_count'] >= 20) {
        $stats['badge'] = 'Expert';
    } elseif ($stats['task_count'] >= 10) {
        $stats['badge'] = 'Intermédiaire';
    }
    
    return $stats;
}

function getLeaderboard() {
    $pdo = getConnexion();
    
    $query = $pdo->prepare("
        SELECT 
            u.nickname,
            COUNT(t.id) as task_count,
            SUM(
                CASE
                    WHEN t.statut = 1 AND t.urgent = 1 AND t.important = 1 THEN 5
                    WHEN t.statut = 1 AND t.urgent = 1 THEN 3
                    WHEN t.statut = 1 AND t.important = 1 THEN 1
                    ELSE 0
                END
            ) AS total_points
        FROM user u
        LEFT JOIN task t ON u.id = t.id_user
        GROUP BY u.id, u.nickname
        ORDER BY total_points DESC, task_count DESC
        LIMIT 10
    ");
    
    $query->execute();
    return $query->fetchAll();
}

// Nouvelle fonction pour juste mettre à jour le statut
function toggleTaskStatus(int $taskId, int $userId) {
    $pdo = getConnexion();
    
    try {
        // Commence une transaction pour s'assurer que tout est cohérent
        $pdo->beginTransaction();
        
        // Récupère d'abord la tâche pour vérifier son statut actuel et ses priorités
        $query = $pdo->prepare("SELECT statut, urgent, important FROM task WHERE id = ? AND id_user = ?");
        $query->execute([$taskId, $userId]);
        $task = $query->fetch();
        
        if ($task) {
            // Inverse le statut
            $newStatus = $task['statut'] ? 0 : 1;
            
            // Met à jour le statut de la tâche
            $query = $pdo->prepare("UPDATE task SET statut = ? WHERE id = ? AND id_user = ?");
            $query->execute([$newStatus, $taskId, $userId]);
        }
        
        // Valide les changements
        $pdo->commit();
        
    } catch (PDOException $e) {
        // En cas d'erreur, annule les modifications
        $pdo->rollBack();
        throw $e;
    }
}