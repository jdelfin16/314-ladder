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

  // Assigning variables in array to parameters
  $winner = $response["winner"];
  $loser = $response["loser"];
  $played = $response["played"];
  */
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
    echo "Game deleted! <br />";
    echo json_encode($result);
  }

  // Testing:
  // print_r($delete_array);
  // delete_game($db, $winner, $loser, $played);
?>
