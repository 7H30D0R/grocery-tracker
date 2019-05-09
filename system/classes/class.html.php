<?php

    if(!defined('LOCAL')) 
    { 
        die('Beklager, men du har ikke adgang til denne fil!'); 
    }
	
	class Html
	{

		public static function page()
		{

			global $config;
			$pageDirectory = 'pages/';

			if (isset($_GET['url']))
			{
				if (isset($_GET['request'])) {
					if (file_exists($pageDirectory . 'request/' . $_GET['url'] . '.php')) {
						include $pageDirectory . 'request/' . $_GET['url'] . '.php';
						return true;
					} else
					{
						include $pageDirectory . 'errors/404.php';
						return false;
					}
				}
				
				if (file_exists($pageDirectory . $_GET['url'] . '.php'))
				{
					include $pageDirectory . $_GET['url'] . '.php';
					return true;
				} else
				{
					include $pageDirectory . 'errors/404.php';
					return false;
				}
			} else
			{
				include $pageDirectory . 'index.php';
				return true;
			}
		}
	}

?>