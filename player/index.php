<?php
	include "../database.php";
	include "../rest.php";
	include "insert.php";
	include "select.php";
	include "update.php";
	include "delete.php";
	include "lib.php";

	$request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

	$response = $request_vars;
	$response["service"] = "player";
	$response["method"] = $method;

	// Arrays for valid_keys() function - based on methods
	$POST_CHECK = array("name", "email", "phone", "username", "password");
	$GET_CHECK = array("username");
	$UPDATE_CHECK = array("username", "name", "email", "rank", "phone");
	$DELETE_CHECK = array("username");

	// Values
	$name = $response["name"];
	$email = $response["email"];
	$phone = $response["phone"];
	$username = $response["username"];
	$password = $response["password"];
	$rank = $response["rank"];

	// D - Delete the Player
	if ($request->isDelete())
	{

	}

	// A - Add the player
	else if ($request->isPost())
	{

	}

	// V - View the Player
	else if ($request->isGet())
	{

	}

	//E - Edit the Player
	else if ($request->isPut())
	{

	}
?>
