<?php
  /*
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "challenge";
  $response["method"] = $method;

  // Arrays for valid_keys() function - based on methods
  $DELETE_CHECK = array("challenger", "challengee", "scheduled");

  // Constants
  $challenger = $response["challenger"];
  $challengee = $response["challengee"];
  $scheduled = $response["scheduled"];
  */

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

  // delete_challenge($db, $challenger, $challengee, $scheduled);
?>
