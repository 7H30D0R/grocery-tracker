<?php

    global $dbh;

    $getProduct = $dbh->prepare('SELECT * FROM products WHERE Ean = :ean');
    $getProduct->bindParam(':ean', $_GET['ean']);
    $getProduct->execute();

    $product = $getProduct->fetch();

    echo ($product) ? (json_encode($product)) : '{ "error": "Product not found" }';

?>