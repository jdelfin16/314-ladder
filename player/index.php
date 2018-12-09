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
		if (verify_parameters($method, $request_vars) == true
      && valid_keys($username, $response, $DELETE_CHECK) == true
      && check_player($db, $username) == true)
    {
      delete_player($db, $username);
    }

    else
    {
      echo json_encode("Unable to delete the player!");
    }
	}

	// A - Add the player
	else if ($request->isPost())
	{
		if (verify_parameters($method, $request_vars) == true
      && valid_keys($name, $response, $POST_CHECK) == true
			&& valid_keys($email, $response, $POST_CHECK) == true
			&& valid_keys($phone, $response, $POST_CHECK) == true
			&& valid_keys($username, $response, $POST_CHECK) == true
			&& valid_keys($password, $response, $POST_CHECK) == true
			&& validate_phone($phone) == true
			&& validate_email($email) == true
      && check_current_player($db, $username) == true)
    {
      insert_player($db, $name, $email, $phone, $username, $password);
    }

    else
    {
      echo json_encode("Unable to insert the player!");
    }
	}

	// V - View the Player
	else if ($request->isGet())
	{
		if (verify_parameters($method, $request_vars) == true
      && valid_keys($username, $response, $GET_CHECK) == true
      && check_player($db, $username) == true)
    {
      select_player($db, $username);
    }

    else
    {
      echo json_encode("Unable to view the player!");
    }
	}

	//E - Edit the Player
	else if ($request->isPut())
	{
		if (verify_parameters($method, $request_vars) == true
      && valid_keys($name, $response, $UPDATE_CHECK) == true
			&& valid_keys($email, $response, $UPDATE_CHECK) == true
			&& valid_keys($phone, $response, $UPDATE_CHECK) == true
			&& valid_keys($username, $response, $UPDATE_CHECK) == true
			&& valid_keys($rank, $response, $UPDATE_CHECK) == true
			&& validate_phone($phone) == true
			&& validate_email($email) == true
      && check_player($db, $username) == true)
    {
			// REMEMBER: ORDER IS IMPORTANT!!!
			update_player($db, $username, $name, $email, $rank, $phone);
    }

    else
    {
      echo json_encode("Unable to update the player!");
    }
	}
?>
