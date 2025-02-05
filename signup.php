

<!--// include "repository/usersRepository.php";-->
<!--// include "config/database.php"; -->


<!--// if(!empty($_POST)) { -->
    
<!--//     $email = $_POST['email'];-->
    
<!--//     $password = $_POST["password"];-->
    
<!--//     $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).{12,}$/';-->
    
<!--//     if (preg_match($regex, $password)) {-->
        
<!--//         $passwordHash = password_hash($password, PASSWORD_DEFAULT);-->
        
<!--//         createUser($email, $passwordHash);-->
        
<!--//     }-->
    
<!--// }-->


<!--// $template = "signup";-->
<!--// include "layout.phtml";-->

<?php

include "config/database.php";
include "repository/usersRepository.php";

if (!empty($_POST)) {

    // Vérifier que le mot de passe est défini
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        
        $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).{12,}$/';
        
        if (preg_match($regex, $password)) {
            
        
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $email = isset($_POST["email"]) ? $_POST["email"] : null;
            $nickname = isset($_POST["nickname"]) ? $_POST["nickname"] : null;
            
            if ($email && $nickname) {

                createUser($email, $nickname, $passwordHash);
                
                echo "Compte créé avec succès !";
                
                header("location:index.php");
                exit;
            } else {
                $error = "L'email ou le pseudo n'est pas valide.";
            }
        } else {
            $error  ="Le mot de passe ne répond pas aux critères de sécurité.";
        }
    } else {
        $error = "Le mot de passe est requis.";
    }
} else {
    $error = "Aucune donnée reçue.";
}


//hasher le mot de passe

//insérer en bdd un utilisateur = appel une fonction qui est dans un repository


$template = "signup";
include "layout.phtml";