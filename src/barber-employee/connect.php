<?php
    $dsn = 'mysql:host=localhost;dbname=web22_eponari19';
	$servername = 'localhost';
    $username = 'eponari19';
    $password = 'ep908ari';
	$database = 'web22_eponari19';
	
	$option = array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	);
	try
	{
		$con = new PDO($dsn,$username,$password);
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		//echo 'Connected!';
	}
	catch(PDOException $ex)
	{
		echo "Failed to connect with database ! ".$ex->getMessage();
		die();
	}
?>