<?php

    if( !defined('LOCAL') 
        || !loggedIn()
        || empty($_POST['id']))
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    $productId = $_POST['id'];

    if ( !empty($_POST['price']) ) Products::updateUserProduct($productId, 'price', $_POST['price']);
    if ( !empty($_POST['name']) ) Products::updateUserProduct($productId, 'name', $_POST['name']);
    if ( !empty($_POST['details']) ) Products::updateUserProduct($productId, 'details', $_POST['details']);
    if ( !empty($_POST['active']) ) Products::updateUserProduct($productId, 'active', $_POST['active']);
    if ( !empty($_POST['amount']) ) Products::updateUserProduct($productId, 'amount', $_POST['amount']);
    if ( !empty($_POST['date_added']) ) Products::updateUserProduct($productId, 'date_added', $_POST['date_added']);

