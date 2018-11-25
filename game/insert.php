<?php

  function insert_game ($connection, $winner, $loser, $played, $winner_score, $loser_score)
  {
    // Fill in $number parameter
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
