<?php

	/* Accessing my database (logging in)
	* 2018-10-23 Note: Logging in to psql postgres...
	*/

	try
	{
		$db = new PDO ("pgsql:dbname=ladder host=localhost password=bitnami user=bitnami");
	}
	catch (PDOException $err)
	{
		print ($err->getMessage());
		echo "<br>";
		exit ("Database error!");
	}
?>
