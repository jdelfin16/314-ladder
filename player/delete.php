<?php

  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;


  // Delete player
  function delete_player ($connection, $username)
  {
    //echo "delete_player check!";
    $sql = "delete from player where username = ?;";

    // Set up query
    $statement = $connection->prepare($sql);

    // Run the query
    $statement->execute ([$username]);

    // Confirmation of deletion
    //echo "$username is deleted! <br>";

    return $statement->rowCount () == 1;
  }

  $test = $_GET['username'];
  echo json_encode(delete_player ($db, $test));
  
?>
