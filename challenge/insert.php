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
  */

  /*
    Creates a challenge between players, scheduled at the given date. The
    newly created challenge is "issued" at the current time.
    Also, challenges are only accepted later via update. The combination of
    challenger, challengee, and scheduled must be unique. Finally, a player
    can only challenge another player:
    - Who are at most ranked 3 spots above (e.g., player #5 can challenge 2, 3, and 4)
    - Who are not ranked below them (since this doesn't really make sense)
    - Who are not a party to an outstanding challenge that has been accepted

  $challenger = $response["challenger"];
  $challengee = $response["challengee"];
  $scheduled = $response["scheduled"];

  */
  function insert_challenge($connection, $challenger, $challengee, $scheduled)
  {
    // Setting up issued
    $issued = date("Y-m-d");

    // Create SQL
    $sql = "insert into challenge (challenger, challengee, issued, scheduled) values
      (:challenger, :challengee, :issued, :scheduled);";

    // Preparing query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':challenger', $challenger);
    $statement->bindParam(':challengee', $challengee);
    $statement->bindParam(':issued', $issued);
    $statement->bindParam(':scheduled', $scheduled);

    $statement->execute ();
    $result = $statement->rowCount () == 1;

    echo json_encode($result);
  }

  // $today = date("Y-m-d");
  // echo $today;
  // insert_challenge($db, $challenger, $challengee, $scheduled);

?>
