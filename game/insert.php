<?php

  function insert_game ($connection, $winner, $loser, $played, $winner_score, $loser_score)
  {
    // Fill in $number parameter

    /*
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
    */
    $number = number_parameter($connection, $winner, $loser);

    // Quit if $number parameter is false
    if ($number == false)
    {
      $fail = false;
      echo json_encode($fail);
    }
    else
    {
      // Create SQL
      $sql = "insert into game (winner, loser, played, number, winner_score,
        loser_score) values (:winner, :loser, :played, :number,
        :winner_score, :loser_score);";

      // Preparing query
      $statement = $connection->prepare($sql);

      // Binding parameters
      $statement->bindParam(':winner', $winner);
      $statement->bindParam(':loser', $loser);
      $statement->bindParam(':played', $played);
      $statement->bindParam(':number', $number);
      $statement->bindParam(':winner_score', $winner_score);
      $statement->bindParam(':loser_score', $loser_score);

      $statement->execute ();
      $result = $statement->rowCount () == 1;

      echo json_encode($result);
    }
  }

?>
