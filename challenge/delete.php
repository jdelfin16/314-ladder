<?php

  // Deletes the challenge between the players
  function delete_challenge($connection, $challenger, $challengee, $scheduled)
  {
    // Query
    $sql = "delete from challenge where challenger = :challenger and challengee = :challengee
      and scheduled = :scheduled;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);
    $statement->bindParam(':scheduled', $scheduled);

    // Run the query
    $statement->execute ();

    // Return / display results
    $result = $statement->rowCount () == 1;
    echo json_encode($result);
  }

?>
