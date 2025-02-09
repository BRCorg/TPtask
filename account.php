<?php

session_start();

if(!isset($_SESSION["user"]) || !isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}

include "config/database.php";
include "repository/tasksRepository.php";


// Gestion de l'ajout de tâche
if (!empty($_POST)) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'toggle_status' && isset($_POST['task_id'])) {
        toggleTaskStatus((int)$_POST['task_id'], $_SESSION['user_id']);
        header('Location: account.php');
        exit();
    }
    elseif ($action === 'delete' && isset($_POST['task_id'])) {
        deleteTask((int)$_POST['task_id'], $_SESSION['user_id']);
        header('Location: account.php');
        exit();
    }
    elseif ($action === 'update' && isset($_POST['task_id'])) {
        $taskId = (int)$_POST['task_id'];
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $urgent = isset($_POST['urgent']);
        $important = isset($_POST['important']);
        
        if ($title) {
            updateTask($taskId, $title, $description, $urgent, $important);
            header('Location: account.php');
            exit();
        }
    }
    else {
        // Code existant pour l'ajout de tâche
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $urgent = isset($_POST['urgent']);
        $important = isset($_POST['important']);
        
        if ($title && isset($_SESSION['user_id'])) {
            createTask($_SESSION['user_id'], $title, $description, $urgent, $important);
            header('Location: account.php');
            exit();
        }
    }
}

// Récupération des tâches de l'utilisateur
$tasks = [];
if (isset($_SESSION['user_id'])) {
    $tasks = getTasksByUserId($_SESSION['user_id']);
}

$template = "account";
include "layout.phtml";
