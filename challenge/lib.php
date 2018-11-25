<?php
  // Library of extra functions
  // for checking and specifically for CRRUD
  /*
  include "../database.php";
	include "../rest.php";

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

  // Constants
  $player = $response["player"];
  $challenger = $response["challenger"];
  $challengee = $response["challengee"];
  $scheduled = $response["scheduled"];
  $accepted = $response["accepted"];
  */
  // Validating keys - using $response value, $response array, and constant arrays set prior
  // Checking if the key is in the given parameters
  function valid_keys($value, $response_array, $constant_array)
  {
    // Enter the parameter and acquire the key - FALSE if empty
    if (empty($value))
    {
      return false;
    }
    else
    {
      $key = array_search($value, $response_array);
      if (in_array($key, $constant_array))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
  }
  // echo json_encode(valid_keys($player, $response, $GET_CHECK_ONE));

  // Function checking the scheduled and accepted dates (ISO 8601 Format w/ and w/o time); returns boolean
  function verify_date($date_time)
  {
    if (json_encode(preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/',
      $date_time, $matches)) == 1)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  // Function checking if the date accepted is appropriate (i.e., it is a date after issued)
  function verify_accepted($connection, $date_time, $challenger, $challengee)
  {
    $date_accepted = strtotime($date_time);

    // Query for issued
    $sql = "select issued from challenge where challenger = :challenger and challengee = :challengee;";

    // Set up query
    $statement = $connection->prepare($sql);
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);

    // Run the query
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $challenge_two = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $challenge_two = [];
    }

    $date_issued = strtotime($challenge_two[0]["issued"]);

    if ($date_issued > $date_accepted) // If the issued date is before the accepted date...
    {
      return false; // The accepted date would be past the issued date --> INVALID
    }
    else
    {
      return true; // The accepted date would be before the issued date --> VALID
    }
  }

  // echo json_encode(verify_accepted($db, $accepted, $challenger, $challengee));

  /*
  echo json_encode(verify_date($accepted));
  echo "<br />";
  echo json_encode(verify_date($scheduled));
  */

  // Function for verifying the player, challenger, or challengee based on the player table
  function check_player ($connection, $player)
  {
    // Querys
    $sql = "select distinct username from player;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Run the query - fetching the columns
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $players = $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    else
    {
      $players = [];
    }

    // If the player is in the array...
    if (in_array($player, $players))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  /*
  echo json_encode(check_player($db, $player));
  echo json_encode(check_player($db, $challenger));
  echo json_encode(check_player($db, $challengee));
  */

  // Function checking if the challenger and challengee are the same player
  function validate_players($challenger, $challengee)
  {
    if ($challenger == $challengee)
    {
      return false;
    }
    else
    {
      return true;
    }
  }

  /*
    Function counting the number of parameters needed for specific methods
    - CREATE and DELETE need 3 parameters
    - GET needs 1 parameter
    - UPDATE needs 4 parameters
  */
  function verify_parameters($method, $response_array)
  {

    // Counting number of parameters
    $num_of_param = count($response_array);

    // If the method is for INSERT...
    if ($method == "POST" && $num_of_param == 3)
    {
      return true;
    }

    // Else, if the method is for SELECT...
    else if ($method == "GET" && $num_of_param == 1)
    {
      return true;
    }

    // Else, if the method is for UPDATE...
    else if ($method == "PUT" && $num_of_param == 4)
    {
      return true;
    }

    // Else, if the method is for DELETE...
    else if ($method == "DELETE" && $num_of_param == 3)
    {
      return true;
    }

    // Else...
    else
    {
      return false;
    }
  }
  // echo json_encode(verify_parameters($method, $request_vars));

  /*
  * BASED ON ASSIGNMENT 3 - Creating query that will be used to validate INSERT
  * What is the function: Essentially a SELECT query...
    * See the rules on the Environment page...
  * Returns boolean
    * If true = challenge exists --> DO NOT INSERT (challenge already exists)
    * If false = challenge does not exist --> INSERT
  */
  function validate_insert($connection, $challenger, $challengee)
  {
    $sql = "select cha.username as challenger, target.username as challengee,
      cha.rank as challenger_rank, target.rank as challengee_rank from player
      as cha, player as target, challenge where (cha.rank - target.rank <= 3)
      and (cha.rank > target.rank) and ((cha.username = challenge.challenger
      and target.username = challenge.challengee) and challenge.accepted is
      not null) and (cha.username = :challenger and target.username = :challengee);";

    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);

    $statement->execute ();
    $result = $statement->rowCount ();

    // echo json_encode($result);
    if ($result == 1)
    {
      return false; // If there already exists a challenge with both players, DO NOT INSERT!
    }
    else
    {
      return true; // If there does not exist a challenge with both players, INSERT!
    }
  }

  // validate_insert($db, $challenger, $challengee);
  // echo json_encode(empty($response["accepted"]));
?>
