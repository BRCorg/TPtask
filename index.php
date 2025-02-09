<?php
session_start();
include "config/database.php";
include "repository/usersRepository.php";

if (!empty($_POST)) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $user = getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['nickname'];
            $_SESSION['user_id'] = $user['id'];
            header('Location: account.php');
            exit();
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    }
}

$template = "index";
include "layout.phtml";