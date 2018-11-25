<?php

  // Validating keys - using $response value, $response array, and constant arrays set prior
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

  // Function returns boolean
  function check_date($date_time)
  {
    if (json_encode(preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/',
      $date_time, $matches)) > 0)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  // Function for verifying the winner and loser
  function check_players ($connection, $winner, $loser)
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

    // Check if the $winner and $loser exist
    if (in_array($winner, $players) && in_array($loser, $players)
      && $winner != $loser)
    {
      return true;
    }
    else
    {
      return false;
    }
  }


  // Function for the number parameter (FOR INSERT!!!)
  function number_parameter($connection, $winner, $loser)
  {
    $verify_players = check_players($connection, $winner, $loser);
    if ($verify_players == false)
    {
      return false;
    }
    else
    {
      // Select ALL of the games involving the valid players
      $sql = "select played from game where (winner = :winner and loser = :loser)
        or (winner = :loser and loser = :winner);";

      $statement = $connection->prepare($sql);
      $statement->bindParam(':winner', $winner);
      $statement->bindParam(':loser', $loser);

      // Return the number of games played
      $statement->execute();
      $curr_number = $statement->rowCount();

      // Increment current count of number
      $new_number = $curr_number + 1;

      return $new_number;
    }
  }

?>
