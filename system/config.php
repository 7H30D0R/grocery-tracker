<?php

    if (!defined('LOCAL')) {
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }

    /* Database Config */
	$db['host'] = 'localhost'; // Database host
	$db['port'] = '3306'; // Database port
	$db['user'] = 'root'; // Database user
	$db['pass'] = ''; // Database password
	$db['db'] = 'dagligtracker'; // Database name

	/* Site Config */
	$config['siteUrl'] = 'http://localhost/barcode';