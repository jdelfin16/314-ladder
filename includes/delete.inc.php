<?php
  session_start();

  // If the submit button was pressed...
  if (isset($_POST['submit']))
  {
    include_once 'database.php';

    // Aqcuire variables from $_POST
    $username = $_POST['user'];

    // Error handlers
    // Check for empty field...
    if (empty($username))
    {
      // Message saying "Empty Parameter"
      header("Location: ../delete.php?exit=empty");
      exit();
    }
    // If field is not empty...
    else
    {
      // Checking if the player exists in the player table
      $find_sql = "select distinct username from player;";
      $find_statement = $db->prepare($find_sql);
      $find_statement->execute();
      if ($find_statement->rowCount() > 0)
      {
        $players = $find_statement->fetchAll(PDO::FETCH_COLUMN);
      }
      else
      {
        $players = [];
      }

      // If the player is not in the array or not the same as the user's username
      if (!in_array($username, $players) || $username != $_SESSION['u_username'])
      {
        // Message saying "Improper players"
        header("Location: ../delete.php?exit=invalid_username");
        exit();
      }
      // If the name is proper, validate the others...
      else
      {

        // After deleting, show that it was successful!

        // Acquiring the rank of the player to be deleted (before it is deleted)
        $rank_sql = "select rank from player where username = :username;";
        $rank_statement = $db->prepare($rank_sql);
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
        $lowest_rank_statement = $db->prepare($lowest_rank_sql);
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

        // Deleting the player

        // Finding the player in challenge...
        $challenge_search_sql = "select * from challenge where challenger = :username or challengee = :username;";
        $challenge_search_statement = $db->prepare($challenge_search_sql);
        $challenge_search_statement->bindParam(':username', $username);
        $challenge_search_statement->execute();

        $challenge_search = $challenge_search_statement->rowCount() >= 1;

        // If the player is not in a challenge...
        if ($challenge_search == false)
        {
          // ...proceed to delete the player from the player table and the game table
          $sql = "delete from player where username = :username;";
          $statement = $db->prepare($sql);
          $statement->bindParam(':username', $username);
          $statement->execute();

          $result = $statement->rowCount () == 1;

          // Update the ranking in player
          for ($i = 1; $i <= ($lowest_rank - $del_rank); $i++)
          {
            $shift_player_sql = "update player set rank = rank - 1 where rank in ($del_rank + $i);";
            $shift_player_statement = $db->prepare($shift_player_sql);
            $shift_player_statement->execute();
          }

          // Delete game
          $game_sql = "delete from game where winner = :username or loser = :username;";
          $game_statement = $db->prepare($game_sql);
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
          $challenge_statement = $db->prepare($challenge_sql);
          $challenge_statement->bindParam(':username', $username);
          $challenge_statement->execute();

          $challenge_result = $challenge_statement->rowCount () == 1;

          // Delete game
          $game_sql = "delete from game where winner = :username or loser = :username;";
          $game_statement = $db->prepare($game_sql);
          $game_statement->bindParam(':username', $username);
          $game_statement->execute();

          $game_result = $game_statement->rowCount () == 1;

          // If challenge is deleted...
          if ($challenge_result == true)
          {
            // ...delete player from player
            // Delete the player from player table
            $sql = "delete from player where username = :username;";
            $statement = $db->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->execute();

            $result = $statement->rowCount () == 1;

            // Update the ranking in player
            for ($i = 1; $i <= ($lowest_rank - $del_rank); $i++)
            {
              $shift_player_sql = "update player set rank = rank - 1 where rank in ($del_rank + $i);";
              $shift_player_statement = $db->prepare($shift_player_sql);
              $shift_player_statement->execute();
            }
          }
        }

        // Log/Sign user out? --> After deleting self, should user be sent back to home page?
        session_unset();
        session_destroy();
        header("Location: ../index.php?exited_ladder");
        exit();

      } // Last else - after checking the username exists in the database
    } // First else - after checking if the parameter is empty...
  } // First if-statement
  // If the submit button was not pressed, send user to the sign up page
  else
  {
    header("Location: ../delete.php");
    exit();
  }
