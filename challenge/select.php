<?php

  /*
    Returns a challenge if the player is involved as either the
    challenger or challengee.
  */
  function select_player($connection, $player)
  {
    $sql = "select * from challenge where challenger = :player or challengee = :player;";

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
      - Return challengees that the given challenger can challenge
  */
  function select_challenger($connection, $challenger)
  {
    $sql = "select name, email, rank, phone, username from player where
    ((select rank from player where username = :challenger) > rank)
    and ((select rank from player where username = :challenger) - rank <= 3);";

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
