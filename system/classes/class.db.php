<?php

	if(!defined('LOCAL')) 
	{ 
		die('Beklager, men du har ikke adgang til denne fil!'); 
    }

	try {
		$dbh = new PDO('mysql:host='.$db['host'].';dbname='.$db['db'].';charset=utf8', $db['user'], $db['pass']);
	}
	catch (PDOException $e) {
		echo ("<h1>Intern fejl</h1>Hjemmesiden kunne ikke oprette forbindelse til databasen!"); 
		die();
	}