<?php
  session_start();

  // If the submit button was pressed...
  if (isset($_POST['submit']))
  {
    include_once 'database.php';

    // Aqcuire variables from $_POST
    $winner = $_POST['winner'];
    $loser = $_POST['loser'];
    $played = $_POST['played'];
    $winner_score = $_POST['winner_score'];
    $loser_score = $_POST['loser_score'];

    // Error handlers
      // Start by checking for bad info...

    // Check for empty fields...
    if (empty($winner) || empty($loser) || empty($played) || empty($winner_score) || empty($loser_score))
    {
      // Message saying "Empty Parameters"
      header("Location: ../game.php?game=empty");
      exit();
    }
    // If fields are not empty, validate the others...
    else
    {
      // Check if the winner and loser are valid (existing)

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

      // If the players are not in the array and are the same person
      if ((!in_array($winner, $players) && !in_array($loser, $players)) && $winner == $loser && ($winner !== $_SESSION['u_username'] || $loser != $_SESSION['u_username']))
      {
        // Message saying "Improper players"
        header("Location: ../game.php?game=invalid_players");
        exit();
      }
      // If the players are proper, validate the others...
      else
      {
        // Check if the winner and loser have an existing challenge

        $exist_sql = "select * from challenge where (challenger = :winner and challengee = :loser) or (challenger = :loser and challengee = :winner);";

        // Set up query
        $exist_statement = $db->prepare($exist_sql);
        $exist_statement->bindParam(':winner', $winner);
        $exist_statement->bindParam(':loser', $loser);

        // Run the query
        $exist_statement->execute();
        if ($exist_statement->rowCount() == 0)
        {
          // Message saying "Improper schedule"
          header("Location: ../game.php?game=no_challenge");
          exit();
        }
        else
        {
          // Check if scheduled is valid.

          // Acquiring scheduled date as time
          $date_scheduled = strtotime($played);

          // Acquiring today's date as time
          $date_issued = strtotime(date("Y-m-d"));

          if (json_encode(preg_match('/^([0-9]{4})-(1[0-2]|0[1-9])-(3[01]|0[1-9]|[12][0-9]) (2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9])$/',
            $played, $matches)) != 1)
          {
            // Message saying "Improper schedule"
            header("Location: ../game.php?game=invalid_played");
            exit();
          }

          else
          {
            if ($winner_score < $loser_score)
            {
              header("Location: ../game.php?game=incorrect_scoring");
              exit();
            }

            else
            {

              // If the challenge has been accepted

              $accepted_sql = "select * from challenge where accepted is not null and (challenger = :winner and challengee = :loser) or (challenger = :loser and challengee = :winner);";

              $accepted_statement = $db->prepare($accepted_sql);
              $accepted_statement->bindParam(':winner', $winner);
              $accepted_statement->bindParam(':loser', $loser);
              $accepted_statement->execute();

              if ($accepted_statement->rowCount() == 0)
              {
                header("Location: ../game.php?game=challenge_unaccepted");
                exit();
              }

              else
              {

                // 1) Insert the game
                // Select ALL of the games involving the valid players
                $select_sql = "select played from game where (winner = :winner and loser = :loser)
                  or (winner = :loser and loser = :winner);";

                $select_statement = $db->prepare($select_sql);
                $select_statement->bindParam(':winner', $winner);
                $select_statement->bindParam(':loser', $loser);

                // Return the number of games played
                $select_statement->execute();
                $curr_number = $select_statement->rowCount();

                // Increment current count of number
                $number = $curr_number + 1;

                // Insert newly incremented game
                $insert_sql = "insert into game (winner, loser, played, number, winner_score,
                  loser_score) values (:winner, :loser, :played, :number,
                  :winner_score, :loser_score);";

                // Preparing query
                $insert_statement = $db->prepare($insert_sql);

                // Binding parameters
                $insert_statement->bindParam(':winner', $winner);
                $insert_statement->bindParam(':loser', $loser);
                $insert_statement->bindParam(':played', $played);
                $insert_statement->bindParam(':number', $number);
                $insert_statement->bindParam(':winner_score', $winner_score);
                $insert_statement->bindParam(':loser_score', $loser_score);

                $insert_statement->execute ();

                // ==================================================================

                // 2) Update player

                // Finding the original rank of the winner (updating)
                $winner_rank_sql = "select rank from player where username = :username;";
                $winner_statement = $db->prepare($winner_rank_sql);
                $winner_statement->bindParam(':username', $winner);
                $winner_statement->execute();

                if ($winner_statement->rowCount() == 1)
                {
                  $winner_result_rank = $winner_statement->fetchAll(PDO::FETCH_ASSOC);
                }
                else
                {
                  $winner_result_rank = [];
                }

                $winner_rank = $winner_result_rank[0]['rank'];

                // Finding the original rank of the loser (target)
                $loser_rank_sql = "select rank from player where username = :username;";
                $loser_statement = $db->prepare($loser_rank_sql);
                $loser_statement->bindParam(':username', $loser);
                $loser_statement->execute();

                if ($loser_statement->rowCount() == 1)
                {
                  $loser_result_rank = $loser_statement->fetchAll(PDO::FETCH_ASSOC);
                }
                else
                {
                  $loser_result_rank = [];
                }

                $loser_rank = $loser_result_rank[0]['rank'];

                if ($winner_rank > $loser_rank)
                {
                  // Set the updated winner's rank to 0
                  $update_winner_sql = "update player set rank = 0 where rank = :rank;";
                  $update_statement = $db->prepare($update_winner_sql);
                  $update_statement->bindParam(':rank', $winner_rank);
                  $update_statement->execute();

                  // Shift the affected players down by one (numerically by adding one)
                  for ($i = 1; $i <= ($winner_rank - $loser_rank); $i++)
                  {
                    $shift_winner_sql = "update player set rank = rank + 1 where rank in ($winner_rank - $i);";
                    $shift_winner_statement = $db->prepare($shift_winner_sql);

                    $shift_winner_statement->execute();
                  }

                  // Set the updated winner's rank to its new rank; update other information
                  $updated_winner_sql = "update player set rank = :rank where rank = 0;";
                  $updated_winner_statement = $db->prepare($updated_winner_sql);
                  $updated_winner_statement->bindParam(':rank', $loser_rank);

                  $updated_winner_statement->execute();
                }

                if ($winner_rank < $loser_rank)
                {
                  // Set the updated winner's rank to 0
                  $update_winner_sql = "update player set rank = 0 where rank = :rank;";
                  $update_statement = $db->prepare($update_winner_sql);
                  $update_statement->bindParam(':rank', $winner_rank);
                  $update_statement->execute();

                  // Shift the affected players down by one (numerically by adding one)
                  for ($i = 1; $i <= ($winner_rank - $loser_rank); $i++)
                  {
                    $shift_winner_sql = "update player set rank = rank - 1 where rank in ($winner_rank + $i);";
                    $shift_winner_statement = $db->prepare($shift_winner_sql);

                    $shift_winner_statement->execute();
                  }

                  // Set the updated winner's rank to its new rank; update other information
                  $updated_winner_sql = "update player set rank = :rank where rank = 0;";
                  $updated_winner_statement = $db->prepare($updated_winner_sql);
                  $updated_winner_statement->bindParam(':rank', $loser_rank);

                  $updated_winner_statement->execute();
                }

                // =================================================================

                // 3) Delete the challenge - allow challengee to be challenged again
                $delete_sql = "delete from challenge where (challenger = :winner and challengee = :loser) or (challenger = :loser and challengee = :winner);";
                $delete_statement = $db->prepare($delete_sql);

                // Binding parameters
                $delete_statement->bindParam(':winner', $winner);
                $delete_statement->bindParam(':loser', $loser);

                // Run the query
                $delete_statement->execute ();

                // After inserting, show that it was successful!
                header("Location: ../game.php?game=success");
                exit();
              }
            }
          }
        } // Validating scheduled
      } // Validating valid players
    } // Validating empty fields
  } // Pressing submit button
  // If the submit button was not pressed, send user to the sign up page
  else
  {
    header("Location: ../game.php");
    exit();
  }
