<?php

session_start();

if(isset($_SESSION['user'])){

    $template = "account";
    include "layout.phtml";
    
}
else{
    header("Location: index.php");
    exit;
}
