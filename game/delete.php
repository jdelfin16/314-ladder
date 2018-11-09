<?php
  /*
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "game";
  $response["method"] = $method;
  */

  // Acquire array of variables
  $delete_array = array();
  foreach ($response as $value)
  {
    $delete_array[] = $value;
  }

  // Assigning variables in array to parameters
  $winner = $delete_array[0];
  $loser = $delete_array[1];
  $played = $delete_array[2];

  // Delete player
  function delete_game ($connection, $winner, $loser, $played)
  {
    // Query
    $sql = "delete from game where winner = :winner and loser = :loser and played = :played;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':winner', $winner);
    $statement->bindParam(':loser', $loser);
    $statement->bindParam(':played', $played);

    // Run the query
    $statement->execute ();

    // Return / display results
    $result = $statement->rowCount () == 1;
    echo json_encode($result);
  }

  // Testing:
  // print_r($request_vars);
  // delete_game($db, $winner, $loser, $played);
?>
