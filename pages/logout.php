<?php

	if ($_SESSION['id'])
	{
		unset($_SESSION['id']);
	}

	header('Location: index');
	die();

?>