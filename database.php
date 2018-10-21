<?php

	// Accessing my database (logging in)
	$servername = "localhost";
	$username = "bitnami";
	$password = "Tiger2010";

	try
	{
		// $db = new PDO ("pgsql:dbname=ladder host=localhost password=Tiger2010 user=bitnami");
	
		$db = new PDO ("pgsql:host=$servername;dbname=ladder", $username, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connection successful!"
	}
	catch (PDOExecption $err)
	{
		//exit ("Database error!");
		echo "Connection error!";
	}
?>