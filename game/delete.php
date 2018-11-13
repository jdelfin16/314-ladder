<?php

  // Delete player
  function delete_game ($connection, $winner, $loser, $played)
  {
    // Query
    $sql = "delete from game where winner = :winner and loser = :loser
      and played = :played;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':winner', $winner);
    $statement->bindParam(':loser', $loser);
    $statement->bindParam(':played', $played);

    // Run the query
    $statement->execute ();

    // Return / display results
    $result = $statement->rowCount ();
    if ($result == 0)
    {
      echo "No game has been deleted! <br />";
    }
    else if ($result > 1)
    {
      echo "Games deleted! <br />";
    }
    else
    {
      echo "Game deleted! <br />";
    }
    echo json_encode($result);
  }

?>
