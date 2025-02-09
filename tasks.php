<?php
session_start();
include "config/database.php";
include "repository/tasksRepository.php";

// Vérifier si l'utilisateur est connecté en vérifiant la présence de user et user_id dans la session
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (!empty($_POST)) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $isUrgent = isset($_POST['is_urgent']);
    $isImportant = isset($_POST['is_important']);
    
    if ($title) {
        createTask($_SESSION['user_id'], $title, $description, $isUrgent, $isImportant);
        header('Location: tasks.php');
        exit();
    }
}

$tasks = getTasksByUserId($_SESSION['user_id']);

$template = "tasks";
include "layout.phtml"; 