<?php
	
	if(!defined('LOCAL')) 
	{ 
		die('Beklager, men du har ikke adgang til denne fil!'); 
	}

	function filter($data) 
	{
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}

	function loggedIn()
	{
		if (isset($_SESSION['id']))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	function requiresLogin($rank = 1)
	{
		global $config, $dbh;
		
		if (!loggedIn() || user::userData('rank') < $rank)
		{
			header('Location: '.$config['siteUrl']);
			die();
		}
	}

	function userBanned()
	{
		if (!loggedIn()) return false;
		global $dbh;

		$getBans = $dbh->prepare('SELECT id FROM bans WHERE user = :user LIMIT 1');
		$getBans->bindParam(':user', $_SESSION['id']);
		$getBans->execute();

		return ($getBans->rowCount() == 0) ? false : true;
	}

	function userIp()
	{
		$ipaddress = '';
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
?>