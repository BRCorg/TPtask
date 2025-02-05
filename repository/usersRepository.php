<?php

function createUser(string $email, string $pseudo, string $password){
    
    $pdo = getConnexion();
    
    $query = $pdo -> prepare("INSERT INTO user (email, pseudo, password) VALUES (?,?,?)");
    
    $query->execute([$email, $pseudo, $password]);
}

function getUserByEmail(string $email){
    
    $pdo = getConnexion();
    
    $query = $pdo -> prepare("SELECT * FROM user WHERE email = ?");
    
    $query->execute([$email]);
    
    return $query->fetch();
}