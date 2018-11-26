<?php

  /*
    Updates the challenge based on the parameters. Updates the accepted field.
    Also, all other outstanding challenges involving the challenger and
    challengee must be deleted.
  */

  function update_challenge($connection, $challenger, $challengee, $scheduled,
    $accepted)
  {
    $sql = "update challenge set accepted = :accepted where challenger = :challenger
      and challengee = :challengee and scheduled = :scheduled;";

    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);
    $statement->bindParam(':scheduled', $scheduled);
    $statement->bindParam(':accepted', $accepted);

    $statement->execute ();
    $result = $statement->rowCount () == 1;

    echo json_encode($result);
  }

?>
