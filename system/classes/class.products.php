<?php

    if(!defined('LOCAL')) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }
	
	class Products
	{

        public static function updateUserProduct($id, $column, $value) {

            global $dbh;

            $allowedColumns = array('price', 'name', 'details', 'active', 'amount', 'date_added');

            if ( !in_array($column, $allowedColumns) ) return;

            $updateProduct = $dbh->prepare('UPDATE user_products SET ' . $column . ' = :value WHERE id = :id AND user_id = :user_id');
            $updateProduct->bindParam(':id', $id);
            $updateProduct->bindParam(':value', $value);
            $updateProduct->bindParam(':user_id', $_SESSION['id']);

            $updateProduct->execute();

            //echo 'UPDATE user_products WHERE id = '. $id .' AND user_id = '. $_SESSION['id'] .' SET ' . $column . ' = '. $value .' <br />';

        }

    }