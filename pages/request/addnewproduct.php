<?php

    if( !defined('LOCAL') 
        || !loggedIn()
        || empty($_POST['barcode'])
        || empty($_POST['name'])
        || empty($_POST['details'])
        || empty($_POST['price'])
        || !is_numeric($_POST['price'])
        || !is_numeric($_POST['barcode'])
        || strlen($_POST['name']) > 60
        || strlen($_POST['details']) > 60
        || strlen($_POST['name']) < 2
        || strlen($_POST['details']) < 2 )
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $getProduct = $dbh->prepare('SELECT * FROM products WHERE Ean = :ean');
    $getProduct->bindParam(':ean', $_POST['barcode']);
    $getProduct->execute();

    $product = $getProduct->fetch();

    if ($product) die('{ "error": "Product already exists" }');
    
    $name = strtoupper(filter($_POST['name']));
    $details = strtoupper(filter($_POST['details']));

    $addNewProduct = $dbh->prepare('INSERT INTO products (Ean, Navn, Navn2, Pris, user_id) VALUES (:ean, :name, :details, :price, :user_id)');
    $addNewProduct->bindParam(':ean', $_POST['barcode']);
    $addNewProduct->bindParam(':name', $name);
    $addNewProduct->bindParam(':details', $details);
    $addNewProduct->bindParam(':price', $_POST['price']);
    $addNewProduct->bindParam(':user_id', $_SESSION['id']);
    $addNewProduct->execute();

    echo '{ "id": "' . $dbh->lastInsertId() . '" }';


