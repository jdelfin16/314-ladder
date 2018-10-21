<?php

// Accessing my database (logging in)
try
{
	$db = new PDO ("pgsql:dbname=ladder host=localhost password=Tiger2010 user=bitnami");
}
catch (PDOExecption $err)
{
	exit("Database error!");
}
?>
