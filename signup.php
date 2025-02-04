<?php

include "repository/usersRepository.php";
include "config/database.php"; 


if(!empty($_POST)) { 
    
    $email = $_POST['email'];
    
    $password = $_POST["password"];
    
    $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).{12,}$/';
    
    if (preg_match($regex, $password)) {
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        createUser($email, $passwordHash);
        
    }
    
}


$template = "signup";
include "layout.phtml";