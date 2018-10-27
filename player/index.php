<?php

	include "../database.php";
	include "../rest.php";
	include "insert.php";
	include "select.php";
	include "delete.php";
	//include "update.php" --> INCOMPLETE...;

	// Constants to check for:
	$USER = "username";
	$NAME = "name";
	$PASS = "password";
	$PHONE = "phone";
	$EMAIL = "email";
	$RANK = "rank";

	$USERNAME = [$USER];
	$ALL = [$USER, $NAME, $PASS, $PHONE, $EMAIL];
	//$UPDATE = [$USER, $NAME, $PHONE, $EMAIL];

	$request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

	/*
	$response = $request_vars;
	$response["service"] = "player";
	$response["method"] = $method;
	*/

	// Return the number of players in the database
	function num_of_players ($connection)
	{
		$sql = "select * from player;";
		$statement = $connection->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}

	// D - Delete the Player
	if ($request->isDelete())
	{
		/* Create a function that checks for errors
		* (ex. missing parameters)
		*/
		//missing_error($request_vars, $USERNAME);

		// Proceed to deleting the player
		// Note: this JSON returns boolean...
		echo json_encode(delete_player ($db, $request_var[$USER]));

	}

	// A - Add the player
	else if ($request->isPost())
	{
		/* Create a function that checks for errors
		* (ex. missing parameters, proper email and phone number formats,
		* repeated player information)
		*/
		//missing_error($request_vars, $ALL);

		// Proceed to adding the new player
		$request_vars[$RANK] = num_of_players ($db) + 1;
		// Note: this JSON returns boolean...
		echo json_encode(insert_player($db, $request_vars));
	}

	// V - View the Player
	else if ($request->isGet())
	{
		/* Create a function that checks for errors
		* (ex. missing username)
		*/
		//missing_error($request_vars, $USERNAME);

		echo json_encode(select_player ($db, $USER));
	}
	/* E - Edit the Player
	else if ($request->isPut())
	{

	}
	*/
?>
