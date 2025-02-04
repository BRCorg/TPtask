<?php

function getUserByEmail(string $email):array|bool {
    
    $pdo = getConnexion();
    
    $query = $pdo->prepare('SELECT * FROM user WHERE email=?');
    //  AND id=? si on veut 2
    
    $query->execute([$email]);
    
    $email = $query->fetch();
    
    return $email;
    
}


function createUser (string $email, string $passwordHash): void {
    
    $pdo = getConnexion();
    
    $query = $pdo->prepare("INSERT INTO user (email, pseudo, password) VALUES (?,?,?)");
    
    $query->execute([$email, $pseudo, $passwordHash]);
    
    
}