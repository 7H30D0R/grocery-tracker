<?php

    if(!defined('LOCAL') || !loggedIn()) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $acceptProducts = $dbh->prepare("UPDATE user_products SET active = '1' WHERE user_id = :user_id");
    $acceptProducts->bindParam(':user_id', $_SESSION['id']);
    $acceptProducts->execute();