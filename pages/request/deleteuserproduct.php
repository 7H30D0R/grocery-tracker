<?php

    if( !defined('LOCAL') 
        || !loggedIn()
        || empty($_POST['id']))
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $getUserProduct = $dbh->prepare('SELECT * FROM user_products WHERE id = :id');
    $getUserProduct->bindParam(':id', $_POST['id']);
    $getUserProduct->execute();

    $userProduct = $getUserProduct->fetch();

    if ( !$userProduct ) die('{ "error": "Product not found!" }');
    if ( $userProduct['user_id'] != $_SESSION['id'] ) die('{ "error": "Product not found!" }');

    try {

        $deleteUserProduct = $dbh->prepare("DELETE FROM user_products WHERE id = :id");
        $deleteUserProduct->bindParam(':id', $_POST['id']);
        
        $deleteUserProduct->execute();

    } catch(PDOException $e) {
        echo $e;
    }

    

