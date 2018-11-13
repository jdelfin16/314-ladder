<?php

  //Get/Select game information
  function select_game ($connection, $username, $played)
  {
    // Query
    $sql = "select * from game where (winner = :username or loser = :username)
      and played = :played;";

    // If there is no 'played' input...
    if (empty($played))
    {
      $sql = "select * from game where (winner = :username or loser = :username);";
    }

    // Set up query
    $statement = $connection->prepare($sql);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':played', $played);

    // Run the query
    $statement->execute();
    if ($statement->rowCount() > 0)
    {
      $game = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $game = [];
    }
    echo "Game selected: <br />";
    echo json_encode($game);
  }

?>
