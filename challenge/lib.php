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

  // Function checking the format of scheduled and accepted dates (ISO 8601 Format w/ and w/o time); returns boolean
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

  // For UPDATE - Function checking if the accepted and scheduled dates are appropriate (i.e., thery're set after issued date)
  function verify_issued($connection, $date_time, $challenger, $challengee)
  {
    // Acquire the accepted / scheduled date as time
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
      $challenge_two = $statement->fetch(PDO::FETCH_NAMED);
    }
    else
    {
      $challenge_two = [];
    }

    $date_issued = strtotime($challenge_two["issued"]);

    if ($date_issued > $date_accepted) // If the issued date is before the accepted/scheduled date...
    {
      return false; // The accepted/scheduled date would be past the issued date --> INVALID
    }
    else
    {
      return true; // The accepted/scheduled date would be before the issued date --> VALID
    }
  }

  // For INSERT - Function checking if the scheduled date is appropriate (i.e., it is set after issued)
  function verify_scheduled($date_time)
  {
    // Acquiring scheduled date as time
    $date_scheduled = strtotime($date_time);

    // Acquiring today's date as time
    $date_issued = strtotime(date("Y-m-d"));

    if ($date_issued > $date_scheduled) // If the issued date is before to the scheduled date...
    {
      return false; // The scheduled date would be past the issued date --> INVALID
    }
    else
    {
      return true; // The scheduled date would be before the issued date --> VALID
    }
  }

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

  /*
  * BASED ON ASSIGNMENT 3 - Creating query that will be used to validate INSERT
  * What is the function: Essentially a SELECT query...
    * See the rules on the Environment page...
  * Returns boolean
    * If true = challenge does not exist --> INSERT
    * If false = challenge does exist --> DO NOT INSERT
  */
  function validate_insert($connection, $challenger, $challengee)
  {
    $sql = "select cha.username as challenger, target.username as challengee,
      cha.rank as challenger_rank, target.rank as challengee_rank
      from player as cha, player as target where (cha.rank - target.rank <= 3)
      and (cha.rank > target.rank) and not exists
      (select * from challenge where challenger = :challenger
      and challengee = :challengee);";

    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);

    $statement->execute ();
    $result = $statement->rowCount ();

    if ($result > 1)
    {
      return true; // If the rules are fulfilled - INSERT!
    }
    else
    {
      return false; // If the rules are NOT fulfilled - DO NOT INSERT!
    }
  }

  // For INSERT - function validating the ranks of the challenger and challengee
    // Following similar format to validate_insert()...
  function validate_ranks($connection, $challenger, $challengee)
  {
    $sql_challenger = "select rank from player where username = :challenger;";
    $sql_challengee = "select rank from player where username = :challengee;";

    $statement_challenger = $connection->prepare($sql_challenger);
    $statement_challengee = $connection->prepare($sql_challengee);

    $statement_challenger->bindParam(':challenger', $challenger);
    $statement_challengee->bindParam(':challengee', $challengee);

    $statement_challenger->execute();
    $statement_challengee->execute();

    if ($statement_challenger->rowCount() == 1)
    {
      $challenger_rank = $statement_challenger->fetch(PDO::FETCH_NAMED);
    }
    else
    {
      $challenger_rank = [];
    }

    if ($statement_challengee->rowCount() == 1)
    {
      $challengee_rank = $statement_challengee->fetch(PDO::FETCH_NAMED);
    }
    else
    {
      $challengee_rank = [];
    }

    $test1 = $challenger_rank["rank"];
    $test2 = $challengee_rank["rank"];

    if (($challenger_rank["rank"] - $challengee_rank["rank"] <= 3)
      && ($challenger_rank["rank"] > $challengee_rank["rank"]))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  // For UPDATE - validate if challenge exist
  function validate_contenders($connection, $challenger, $challengee)
  {
    // Querys
    $sql = "select challenger, challengee from challenge where challenger = :challenger and challengee = :challengee;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);

    // Run the query - fetching the columns
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $valid_challenge = $statement->fetch(PDO::FETCH_NAMED);
    }
    else
    {
      $valid_challenge = [];
    }

    // If the players are valid challengers and challengees...
      // If the challenge is valid...
    if (in_array($challenger, $valid_challenge) && in_array($challengee, $valid_challenge))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  // For SELECT - validate if the challenger exists
  function validate_challenger($connection, $challenger)
  {
    $sql = "select distinct challenger from challenge;";

    $statement = $connection->prepare($sql);

    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $list_challengers = $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    else
    {
      $list_challengers = [];
    }

    // If the challenger is in challenge...
    if (in_array($challenger, $list_challengers))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
?>
