<?php

    if(!defined('LOCAL') || !loggedIn()) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $acceptProducts = $dbh->prepare("DELETE FROM user_products WHERE user_id = :user_id AND active = '0'");
    $acceptProducts->bindParam(':user_id', $_SESSION['id']);
    $acceptProducts->execute();