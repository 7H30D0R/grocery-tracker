<?php

    if( !defined('LOCAL') 
        || !loggedIn()
        || empty($_POST['barcode'])
        || empty($_POST['price'])
        || empty($_POST['amount']) )
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    global $dbh;

    $getProductDetails = $dbh->prepare('SELECT * FROM products WHERE Ean = :barcode');
    $getProductDetails->bindParam(':barcode', $_POST['barcode']);
    $getProductDetails->execute();

    $productDetails = $getProductDetails->fetch();

    try {

        $saveUserProduct = $dbh->prepare("INSERT INTO user_products (barcode, price, `name`, details, active, `user_id`, amount, date_added) VALUES (:barcode, :price, :product_name, :details, '0', :userid, :amount, :epoch)");
        $saveUserProduct->bindParam(':barcode', $_POST['barcode']);
        $saveUserProduct->bindParam(':price', $_POST['price']);
        $saveUserProduct->bindParam(':product_name', $productDetails['Navn']);
        $saveUserProduct->bindParam(':details', $productDetails['Navn2']);
        $saveUserProduct->bindParam(':userid', $_SESSION['id']);
        $saveUserProduct->bindParam(':amount', $_POST['amount']);
        $saveUserProduct->bindParam(':epoch', time());

        $saveUserProduct->execute();

        echo '{ "id": "' . $dbh->lastInsertId() . '" }';

    } catch(PDOException $e) {
        echo $e;
    }

    

