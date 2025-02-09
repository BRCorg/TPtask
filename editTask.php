<?php
session_start();
include "config/database.php";
include "repository/tasksRepository.php";

if(!isset($_SESSION["user"]) || !isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}

$taskId = $_GET['id'] ?? null;
$task = null;

if($taskId) {
    $task = getTaskById($taskId, $_SESSION['user_id']);
}

if(!$task) {
    header("location:account.php");
    exit;
}

if(!empty($_POST)) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $urgent = isset($_POST['urgent']);
    $important = isset($_POST['important']);
    
    if($title) {
        updateTask($taskId, $title, $description, $urgent, $important);
        header("location:account.php");
        exit;
    }
}

$template = "editTask";
include "layout.phtml"; 