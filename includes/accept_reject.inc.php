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
    $choice = $_POST['choice'];

    // Error handlers
      // Start by checking for bad info...

    // Check for empty fields...
    if (empty($challenger) || empty($challengee) || empty($scheduled) || empty($choice))
    {
      // Message saying "Empty Parameters"
      header("Location: ../accept_reject.php?choice=empty");
      exit();
    }
    // If fields are not empty, validate the others...
    else
    {

      // Check if the challenge between the players exist
      $search_sql = "select * from challenge where challenger = :challenger and challengee = :challengee and scheduled = :scheduled";
      $search_statement = $db->prepare($search_sql);
      $search_statement->bindParam(':challenger', $challenger);
      $search_statement->bindParam(':challengee', $challengee);
      $search_statement->bindParam(':scheduled', $scheduled);

      $search_statement->execute();

      if ($search_statement->rowCount() == 0)
      {
        // Message saying "Challenge nonexistant"
        header("Location: ../accept_reject.php?choice=no_challenge");
        exit();
      }

      else
      {
        // Check if the challenger and challengee are valid

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
          header("Location: ../accept_reject.php?choice=invalid_players");
          exit();
        }
        // If the players are proper, validate the others...
        else
        {
          // Check if scheduled is valid.

          // Acquiring scheduled date as time
          $date_scheduled = strtotime($scheduled);

          // Acquiring today's date as time
          $date_issued = strtotime(date("Y-m-d"));

          if (json_encode(preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/',
            $scheduled, $matches)) != 1 || ($date_issued > $date_scheduled))
          {
            // Message saying "Improper schedule"
            header("Location: ../accept_reject.php?choice=invalid_scheduled");
            exit();
          }
          // If the scheduled is proper, proceed to make choice
          else
          {
            // Challenger cannot accept what he/she imposed on challengee...
              // Only the challengee can accept the challenge

            if ($challengee != $_SESSION['u_username'])
            {
              // Message saying "Not challengee"
              header("Location: ../accept_reject.php?choice=challengee_only");
              exit();
            }

            else
            {
              if ($choice == "accept")
              {
                // Update the challenge's accepted data
                $date_accepted = date("Y-m-d");

                $sql = "update challenge set accepted = :accepted where challenger = :challenger
                  and challengee = :challengee and scheduled = :scheduled;";

                $statement = $db->prepare($sql);

                // Binding parameters
                $statement->bindParam(':challenger', $challenger);
                $statement->bindParam(':challengee', $challengee);
                $statement->bindParam(':scheduled', $scheduled);
                $statement->bindParam(':accepted', $date_accepted);

                $statement->execute ();

                header("Location: ../accept_reject.php?choice=accepted");
              }
              elseif ($choice == "reject")
              {
                // Delete the challenge
                $sql = "delete from challenge where challenger = :challenger and challengee = :challengee
                  and scheduled = :scheduled;";

                // Set up query
                $statement = $db->prepare($sql);

                // Binding parameters
                $statement->bindParam(':challenger', $challenger);
                $statement->bindParam(':challengee', $challengee);
                $statement->bindParam(':scheduled', $scheduled);

                // Run the query
                $statement->execute ();
                header("Location: ../accept_reject.php?choice=rejected");
              }
              // After deleting, show that it was successful!
              exit();
            }
          }
        } // Validating scheduled
      } // Validating valid players
    } // Validating empty fields
  } // Pressing submit button
  // If the submit button was not pressed, send user to the sign up page
  else
  {
    header("Location: ../accept_reject.php");
    exit();
  }
