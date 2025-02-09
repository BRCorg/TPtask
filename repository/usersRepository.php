<?php

function createUser(string $email, string $nickname, string $password){
    
    $pdo = getConnexion();
    
    $query = $pdo -> prepare("INSERT INTO user (email, nickname, password) VALUES (?,?,?)");
    
    $query->execute([$email, $nickname, $password]);
}

function getUserByEmail(string $email){
    
    $pdo = getConnexion();
    
    $query = $pdo -> prepare("SELECT * FROM user WHERE email = ?");
    
    $query->execute([$email]);
    
    return $query->fetch();
}

function deleteUser(int $userId) {
    
    $pdo = getConnexion();
    
    $query = $pdo->prepare("DELETE FROM user WHERE id = ?");
    
    $query->execute([$userId]);
}