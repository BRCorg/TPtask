<?php

function getConnexion():object
{
    $pdo = new PDO('mysql:host=db.3wa.io;port=3306;dbname=djibrilhadef_TPtask;charset=utf8', 'djibrilhadef', '9986716826ac5f952ab11765749636a7');
    
    return $pdo;
}