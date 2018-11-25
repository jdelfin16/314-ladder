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
  $response["service"] = "challenge";
  $response["method"] = $method;

  // Arrays for valid_keys() function - based on methods
  $POST_CHECK = array("challenger", "challengee", "scheduled");
  $GET_CHECK_ONE = array("player");
  $GET_CHECK_TWO = array("challenger");
  $UPDATE_CHECK = array("challenger", "challengee", "scheduled", "accepted");
  $DELETE_CHECK = array("challenger", "challengee", "scheduled");

  // Values
  $player = $response["player"];
  $challenger = $response["challenger"];
  $challengee = $response["challengee"];
  $scheduled = $response["scheduled"];
  $accepted = $response["accepted"];

  // D - Delete the Challenge
	if ($request->isDelete())
	{
    if (verify_parameters($method, $request_vars) == true
      && valid_keys($challenger, $response, $DELETE_CHECK) == true
      && valid_keys($challengee, $response, $DELETE_CHECK) == true
      && valid_keys($scheduled, $response, $DELETE_CHECK) == true
      && check_player($db, $challenger) == true
      && check_player($db, $challengee) == true
      && validate_players($challenger, $challengee) == true
      && verify_date($scheduled) == true)
    {
      delete_challenge($db, $challenger, $challengee, $scheduled);
    }

    else
    {
      echo json_encode("ERROR: Unable to delete the challenge!");
    }
	}

	// A - Add the Challenge
	else if ($request->isPost())
	{
    if (verify_parameters($method, $request_vars) == true
      && valid_keys($challenger, $response, $POST_CHECK) == true
      && valid_keys($challengee, $response, $POST_CHECK) == true
      && valid_keys($scheduled, $response, $POST_CHECK) == true
      && check_player($db, $challenger) == true
      && check_player($db, $challengee) == true
      && validate_players($challenger, $challengee) == true
      && verify_date($scheduled) == true
      && validate_insert($db, $challenger, $challengee) == true
      && validate_players($challenger, $challengee) == true)
    {
      insert_challenge($db, $challenger, $challengee, $scheduled);
    }

    else
    {
      echo json_encode("ERROR: Unable to insert the challenge!");
    }
	}

	// V - View the Challenge
	else if ($request->isGet())
	{
    if (verify_parameters($method, $request_vars) == true
      && valid_keys($player, $response, $GET_CHECK_ONE) == true
      && check_player($db, $player) == true)
    {
      select_player($db, $player);
    }

    else if (verify_parameters($method, $request_vars) == true
      && valid_keys($challenger, $response, $GET_CHECK_TWO) == true
      && check_player($db, $challenger) == true)
    {
      select_challenger($db, $challenger);
    }

    else
    {
      echo json_encode("ERROR: Unable to select the challenge!");
    }
	}

  // E - Edit the Challenge
  else if ($request->isPut())
  {
    if (verify_parameters($method, $request_vars) == true
      && valid_keys($challenger, $response, $UPDATE_CHECK) == true
      && valid_keys($challengee, $response, $UPDATE_CHECK) == true
      && valid_keys($scheduled, $response, $UPDATE_CHECK) == true
      && valid_keys($accepted, $response, $UPDATE_CHECK) == true
      && check_player($db, $challenger) == true
      && check_player($db, $challengee) == true
      && validate_players($challenger, $challengee) == true
      && verify_date($scheduled) == true
      && verify_date($accepted) == true
      && verify_accepted($db, $accepted, $challenger, $challengee) == true)
    {
      update_challenge($db, $challenger, $challengee, $scheduled, $accepted);
    }

    else
    {
      echo json_encode("ERROR: Unable to update the challenge!");
    }
  }

?>
