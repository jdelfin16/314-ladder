<?php
  /*
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;

  $DELETE_CHECK = array("username");
  $username = $response["username"];
  */
  function delete_player($connection, $username)
  {
    // Acquiring the rank of the player to be deleted (before it is deleted)
    $rank_sql = "select rank from player where username = :username;";
    $rank_statement = $connection->prepare($rank_sql);
    $rank_statement->bindParam(':username', $username);
    $rank_statement->execute();
    if ($rank_statement->rowCount() == 1)
    {
      $result_rank = $rank_statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $result_rank = [];
    }

    $del_rank = $result_rank[0]['rank'];

    // Acquiring the lowest (numerically highest) rank
    $lowest_rank_sql = "select rank from player order by rank desc limit 1;";
    $lowest_rank_statement = $connection->prepare($lowest_rank_sql);
    $lowest_rank_statement->execute();
    if ($lowest_rank_statement->rowCount() == 1)
    {
      $result_low_rank = $lowest_rank_statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $result_low_rank = [];
    }

    $lowest_rank = $result_low_rank[0]['rank'];

    /* Deleting the player
      Note: Unable to delete players if they exist in the challenge table...
      - Need to find if there exists a challenge with the player to be deleted
      - If found, delete that player from the challenge FIRST, and then delete that player from everywhere else...
    */

    // Finding the player in challenge...
    $challenge_search_sql = "select * from challenge where challenger = :username or challengee = :username;";
    $challenge_search_statement = $connection->prepare($challenge_search_sql);
    $challenge_search_statement->bindParam(':username', $username);
    $challenge_search_statement->execute();

    $challenge_search = $challenge_search_statement->rowCount() >= 1;

    // If the player is not in a challenge...
    if ($challenge_search == false)
    {
      // ...proceed to delete the player from the player table and the game table
      $sql = "delete from player where username = :username;";
      $statement = $connection->prepare($sql);
      $statement->bindParam(':username', $username);
      $statement->execute();

      $result = $statement->rowCount () == 1;

      // Update the ranking in player
      for ($i = 1; $i <= ($lowest_rank - $del_rank); $i++)
      {
        $shift_player_sql = "update player set rank = rank - 1 where rank in ($del_rank + $i);";
        $shift_player_statement = $connection->prepare($shift_player_sql);
        $shift_player_statement->execute();
      }

      // Delete game
      $game_sql = "delete from game where winner = :username or loser = :username;";
      $game_statement = $connection->prepare($game_sql);
      $game_statement->bindParam(':username', $username);
      $game_statement->execute();

      $game_result = $game_statement->rowCount () == 1;

      // Delete: COMPLETE
      echo json_encode($result);
    }

    // If the player is in a challenge...
    else
    {
      // ...delete challenge FIRST
      $challenge_sql = "delete from challenge where challenger = :username or challengee = :username;";
      $challenge_statement = $connection->prepare($challenge_sql);
      $challenge_statement->bindParam(':username', $username);
      $challenge_statement->execute();

      $challenge_result = $challenge_statement->rowCount () == 1;

      // Delete game
      $game_sql = "delete from game where winner = :username or loser = :username;";
      $game_statement = $connection->prepare($game_sql);
      $game_statement->bindParam(':username', $username);
      $game_statement->execute();

      $game_result = $game_statement->rowCount () == 1;

      // If challenge is deleted...
      if ($challenge_result == true)
      {
        // ...delete player from player
        // Delete the player from player table
        $sql = "delete from player where username = :username;";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->rowCount () == 1;

        // Update the ranking in player
        for ($i = 1; $i <= ($lowest_rank - $del_rank); $i++)
        {
          $shift_player_sql = "update player set rank = rank - 1 where rank in ($del_rank + $i);";
          $shift_player_statement = $connection->prepare($shift_player_sql);
          $shift_player_statement->execute();
        }

        // Delete: COMPLETE
        echo json_encode($result);

      }
    }
  }
?>
