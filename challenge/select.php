<?php

  /*
    Returns a challenge if the player is involved as either the
    challenger or challengee.
  */
  function select_player($connection, $player)
  {
    $sql = "select * from challenge where challenger = :player or challengee = :player";

    // Set up query
    $statement = $connection->prepare($sql);
    $statement->bindParam(':player', $player);

    // Run the query
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $challenge_one = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $challenge_one = [];
    }
    echo json_encode($challenge_one);
  }

  /*
    An alternative challenge read operation, if the "challenger" parameter
    is given, return players available for that player to challenge.
    The "challenger" field should contain a username.
      - Return if the selected challenger has available parameter
  */
  function select_challenger($connection, $challenger)
  {
    $sql = "select * from challenge where challenger = :challenger
      and accepted is not null;";

    // Set up query
    $statement = $connection->prepare($sql);
    $statement->bindParam(':challenger', $challenger);

    // Run the query
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $challenge_two = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $challenge_two = [];
    }
    echo json_encode($challenge_two);
  }

?>
