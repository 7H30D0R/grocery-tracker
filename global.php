<?php
    
	if(!defined('LOCAL')) 
	{ 
		die('Beklager, men du har ikke adgang til denne fil!'); 
	}
	
	ob_start();
	session_start();
	
    require_once 'system/config.php';

	require_once 'system/classes/functions.php';
	require_once 'system/classes/class.db.php';
	require_once 'system/classes/class.html.php';
	require_once 'system/classes/class.user.php';
	require_once 'system/classes/class.products.php';
    
?>