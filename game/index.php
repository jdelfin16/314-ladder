<?php
  include "../database.php";
	include "../rest.php";
	include "insert.php";
	include "select.php";
	include "delete.php";
  include "checking.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "game";
  $response["method"] = $method;

  // Array for array_key_exists() function - based on methods
  $GET_CHECK = array("username");
  $POST_CHECK = array("winner", "loser", "played", "winner_score", "loser_score");
  $DELETE_CHECK = array("winner", "loser", "played");

  // Values for Get
  $username = $response["username"];

  // Values for POST and DELETE
  $winner = $response["winner"];
  $loser = $response["loser"];
  $played = $response["played"];
  $number = $response["number"];
  $winner_score= $response["winner_score"];
  $loser_score= $response["loser_score"];

  // Counting number of parameters
  $num_of_param = count($request_vars);

  // D - Delete the Player
	if ($request->isDelete())
	{
    if (check_players($db, $winner, $loser) == true
      && valid_keys($winner, $response, $DELETE_CHECK) == true
      && valid_keys($loser, $response, $DELETE_CHECK) == true
      && valid_keys($played, $response, $DELETE_CHECK) == true
      && check_date($played) == true
      && $num_of_param == 3)
    {
      delete_game($db, $winner, $loser, $played);
    }
    else
    {
      echo "ERROR: Unable to delete game!";
    }
	}

	// A - Add the player
	else if ($request->isPost())
	{
    if (check_players($db, $winner, $loser) == true
      && valid_keys($winner, $response, $POST_CHECK) == true
      && valid_keys($loser, $response, $POST_CHECK) == true
      && valid_keys($played, $response, $POST_CHECK) == true
      && valid_keys($winner_score, $response, $POST_CHECK) == true
      && valid_keys($loser_score, $response, $POST_CHECK) == true
      && check_date($played) == true
      && $num_of_param == 5)
    {
      insert_game($db, $winner, $loser, $played, $winner_score, $loser_score);
    }
    else
    {
      echo "ERROR: Unable to insert game!";
    }
	}

	// V - View the Player
	else if ($request->isGet())
	{
    if (valid_keys($username, $response, $GET_CHECK) == true
      && ($num_of_param == 1 || $num_of_param == 2))
    {
      select_game ($db, $username, $played);
    }
    else
    {
      echo "ERROR: Unable to select game!";
    }
	}

?>
