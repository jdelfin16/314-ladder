<?php
  session_start();

  // If the submit button was pressed...
  if (isset($_POST['submit']))
  {
    include_once 'database.php';

    // Aqcuire variables from $_POST
    $challenger = $_POST['challenger'];
    $challengee = $_POST['challengee'];
    $scheduled = $_POST['scheduled'];


    // Error handlers
      // Start by checking for bad info...

    // Check for empty fields...
    if (empty($challenger) || empty($challengee) || empty($scheduled))
    {
      // Message saying "Empty Parameters"
      header("Location: ../challenge.php?challenge=empty");
      exit();
    }
    // If fields are not empty, validate the others...
    else
    {
      // Check if the challenger and challengee are valids to challenge each other

      // Querys for challenger and challengee
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
      if ((!in_array($challenger, $players) && !in_array($challengee, $players)) && $challenger == $challengee)
      {
        // Message saying "Improper players"
        header("Location: ../challenge.php?challenge=invalid_players");
        exit();
      }
      // If the players are proper, validate the others...
      else
      {
        // Check if the challenger is the user
        if ($challenger != $_SESSION['u_username'])
        {
          header("Location: ../challenge.php?challenge=invalid_challenger");
          exit();
        }
        else
        {
          // Check if scheduled is valid.

          // Acquiring scheduled date as time
          $date_scheduled = strtotime($scheduled);

          // Acquiring today's date as time
          $date_issued = strtotime(date("Y-m-d"));

          if (json_encode(preg_match('/^([0-9]{4})-(1[0-2]|0[1-9])-(3[01]|0[1-9]|[12][0-9]) (2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9])$/',
            $scheduled, $matches)) != 1 || ($date_issued > $date_scheduled))
          {
            // Message saying "Improper schedule"
            header("Location: ../challenge.php?challenge=invalid_scheduled");
            exit();
          }
          // If the scheduled is proper, validate the others...
          else
          {
            // Check if the rules are fulfilled
            $rules_sql = "select cha.username as challenger, target.username as challengee,
              cha.rank as challenger_rank, target.rank as challengee_rank
              from player as cha, player as target where (cha.rank - target.rank <= 3)
              and (cha.rank > target.rank) and not exists
              (select * from challenge where challenger = :challenger
              and challengee = :challengee);";

            $rules_statement = $db->prepare($rules_sql);
            $rules_statement->bindParam(':challenger', $challenger);
            $rules_statement->bindParam(':challengee', $challengee);
            $rules_statement->execute ();
            $rules_fulfilled = $rules_statement->rowCount ();

            if ($rules_fulfilled == 0)
            {
              // Message saying "Rules not fulfilled"
              header("Location: ../challenge.php?challenge=already_exists");
              exit();
            }
            // If the rules are fulfilled, validate the others...
            else
            {
              // Check if the rules for rank difference is valid

              $sql_challenger = "select rank from player where username = :challenger;";
              $sql_challengee = "select rank from player where username = :challengee;";

              $statement_challenger = $db->prepare($sql_challenger);
              $statement_challengee = $db->prepare($sql_challengee);

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

              if ($challenger_rank["rank"] - $challengee_rank["rank"] > 3 || $challenger_rank["rank"] <= $challengee_rank["rank"])
              {
                // Message saying "Rules for rank is not fulfilled"
                header("Location: ../challenge.php?challenge=rules__ranks_unfulfilled");
                exit();
              }
              // If the rule for ranks is valid, proceed to insert the data
              else
              {
                // If the challengee has already accepted a challenge
                $accept_sql = "select challengee from challenge where accepted is not null and challengee = :player;";

                // Set up query
                $accept_statement = $db->prepare($accept_sql);
                $accept_statement->bindParam(':player', $challengee);

                // Run the query
                $accept_statement->execute();
                if ($accept_statement->rowCount() > 0)
                {
                  header("Location: ../challenge.php?challenge=conflict_challenge");
                  exit();
                }
                else
                {
                  // Setting up issued
                  $issued = date("Y-m-d");

                  // Create SQL
                  $sql = "insert into challenge (challenger, challengee, issued, scheduled) values
                    (:challenger, :challengee, :issued, :scheduled);";

                  // Preparing query
                  $statement = $db->prepare($sql);

                  // Binding parameters
                  $statement->bindParam(':challenger', $challenger);
                  $statement->bindParam(':challengee', $challengee);
                  $statement->bindParam(':issued', $issued);
                  $statement->bindParam(':scheduled', $scheduled);

                  $statement->execute ();

                  // After inserting, show that it was successful!
                  header("Location: ../challenge.php?challenge=success");
                  exit();
                }
              }
            }
          }
        }
      }
    }
  }
  // If the submit button was not pressed, send user to the sign up page
  else
  {
    header("Location: ../challenge.php");
    exit();
  }
