<?php


include "config/database.php";
include "repository/usersRepository.php";


//démarrage du système de session
session_start();

//si le form a été soumis ($_POST n'est pas vide)
if(!empty($_POST)){
    
    //appel à la bdd
    //1 créer une fonction qui permet d'aller cherche le mdp haché d'un user par son email
    $user = getUserByEmail($_POST["email"]);
    
    if($user){
         //si l'utilisateur a saisi les bons identifiants et mots de passe
        if(password_verify($_POST['password'], $user["password"])){
            
            //création d'une session
            $_SESSION["user"] = $user["nickname"];
            
            //redirection vers la page top secrète
            header("Location:account.php");
            exit;
        }
        else{
            $error = "Identifiant ou mot de passe incorrect";
        }
    }
    else{
         $error = "Identifiant ou mot de passe incorrect";
    }
    
    //2 comparer le hash avec le mdp saisi dans le form 
        //si c'est ok, on créer la session
        //si pas ok, on affiche une erreur 
    
   
}

// if(isset($_SESSION["user"]) && $_SESSION["user"] === "admin"){
//       header("Location:secret.php");
//         exit;
// }


$template = "connexion";
include "layout.phtml";