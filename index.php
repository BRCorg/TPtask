<?php 

require_once "config/database.php";
require_once "repository/usersRepository.php";

session_start();



if(!empty($_POST)) {
    
    $user = getUserByEmail($_POST['email']);
    
    
    if($user) {
        if(!password_verify($_POST['password'], $user['password'])) {
            
           $_SESSION["user"] = $user["username"];
           
            echo "Votre utilisateur ou Mot de passe est erroné";
            
            // echo $error
        }else {
            
            $_SESSION["email"] = trim($_POST["email"]);
            
            header("location:secret.php");
            exit;
            
        }
    
}else {
    $error =  "Votre Email ou Mot de passe est incorrect";
}
}else {
    $error = "Veuillez crée un compte";
}

    

// if(isset($_SESSION["user"]) && $_SESSION["user"] === "admin"){
//       header("Location:secret.php");
//         exit;
// }



$template = "index";
include "layout.phtml";