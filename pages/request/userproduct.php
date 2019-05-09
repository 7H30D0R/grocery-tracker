<?php

    if(!defined('LOCAL') 
        || !loggedIn()
        || empty($_POST['id'])) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $getProduct = $dbh->prepare('SELECT * FROM user_products WHERE id = :id');
    $getProduct->bindParam(':id', $_POST['id']);
    $getProduct->execute();

    $product = $getProduct->fetch();

    if (!$product) echo '{ "error": "Product not found" }';
    else if ($product['user_id'] != $_SESSION['id']) echo '{ "error": "Product not found" }';
    else echo (json_encode($product));