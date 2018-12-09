<?php
  /*
    Updates the player with the given parameters. All the players between the
    old and new rank need to be shifted either up or down depending on the change.
      - Hint: username is the only unupdatable parameter...
  */
  function update_player($connection, $username, $name, $email, $rank, $phone)
  {
    // Finding the original rank of the player to be updated
    $original_rank_sql = "select rank from player where username = :username;";
    $original_statement = $connection->prepare($original_rank_sql);
    $original_statement->bindParam(':username', $username);
    $original_statement->execute();

    if ($original_statement->rowCount() == 1)
    {
      $result_rank = $original_statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $result_rank = [];
    }

    $original_rank = $result_rank[0]['rank'];


    // If there is no rearrangement (i.e., $original_rank and $rank are the same), update other information...
    if ($original_rank == $rank)
    {
      // Update the other information
      $sql = "update player set name = :name, email = :email, rank = :rank,
        phone = :phone where username = :username;";

      $statement = $connection->prepare($sql);
      $statement->bindParam(':username', $username);
      $statement->bindParam(':name', $name);
      $statement->bindParam(':email', $email);
      $statement->bindParam(':rank', $rank);
      $statement->bindParam(':phone', $phone);

      $statement->execute ();
      $result = $statement->rowCount () == 1;

      echo json_encode($result);
    }

    // =======================================================================

    // If the updated player is shifting to a higher rank (numerically lower than original...)
    if ($original_rank > $rank)
    {
      // Set the updated player's rank to 0
      $update_player_sql = "update player set rank = 0 where rank = :rank;";
      $update_statement = $connection->prepare($update_player_sql);
      $update_statement->bindParam(':rank', $original_rank);
      $update_statement->execute();

      // Shift the affected players down by one (numerically by adding one)
      for ($i = 1; $i <= ($original_rank - $rank); $i++)
      {
        $shift_player_sql = "update player set rank = rank + 1 where rank in ($original_rank - $i);";
        $shift_player_statement = $connection->prepare($shift_player_sql);

        $shift_player_statement->execute();
      }

      // Set the updated player's rank to its new rank; update other information
      $updated_player_sql = "update player set name = :name, email = :email, rank = :rank,
        phone = :phone where username = :username and rank = 0;";
      $updated_statement = $connection->prepare($updated_player_sql);
      $updated_statement->bindParam(':rank', $rank);
      $updated_statement->bindParam(':username', $username);
      $updated_statement->bindParam(':name', $name);
      $updated_statement->bindParam(':email', $email);
      $updated_statement->bindParam(':phone', $phone);

      $updated_statement->execute();

      $result = $updated_statement->rowCount() == 1;

      echo json_encode($result);
    }

    // =======================================================================

    // If the updated player is shifting to a lower rank (numerically higher than original...)
    if ($original_rank < $rank)
    {
      // Set the updated player's rank to 0
      $update_player_sql = "update player set rank = 0 where rank = :rank;";
      $update_statement = $connection->prepare($update_player_sql);
      $update_statement->bindParam(':rank', $original_rank);
      $update_statement->execute();

      // Shift the affected players down by one (numerically by adding one)
      for ($i = 1; $i <= ($rank - $original_rank); $i++)
      {
        $shift_player_sql = "update player set rank = rank - 1 where rank in ($original_rank + $i);";
        $shift_player_statement = $connection->prepare($shift_player_sql);
        $shift_player_statement->execute();
      }

      // Set the updated player's rank to its new rank; update other information
      $updated_player_sql = "update player set name = :name, email = :email, rank = :rank,
        phone = :phone where username = :username and rank = 0;";
      $updated_statement = $connection->prepare($updated_player_sql);
      $updated_statement->bindParam(':rank', $rank);
      $update_statement->bindParam(':username', $username);
      $updated_statement->bindParam(':name', $name);
      $updated_statement->bindParam(':email', $email);
      $updated_statement->bindParam(':phone', $phone);
      $updated_statement->execute();

      $result = $updated_statement->rowCount() == 1;

      echo json_encode($result);
    }
  }

?>
