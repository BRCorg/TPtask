<?php
session_start();
include "config/database.php";
include "repository/tasksRepository.php";

if(!isset($_SESSION["user"])) {
    header("location:index.php");
    exit;
}

$leaderboard = getLeaderboard();
$userStats = getUserStats($_SESSION['user_id']);

$template = "leaderboard";
include "layout.phtml"; 