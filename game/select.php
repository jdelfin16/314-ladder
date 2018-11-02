<?php

  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;


  $response["service"] = "game";
  $response["method"] = $method;


  //Get/Select game information
  function select_game ($connection, $username, $played)
  {
    // Query
    $sql = "select * from game where (winner = $username or loser = $username) and played = $played;";

     // If there is no 'played' input...
     if (empty($played))
     {
       $sql = "select * from game where (winner = $username or loser = $username);";
     }

    // Set up query
    $statement = $connection->prepare($sql);

    // Run the query
    $statement->execute ([$username]);
    if ($statement->rowCount() > 0)
    {
      $player = $statement->fetch(PDO::FETCH_ASSOC);
    }
    else {
      $player = [];
    }
    echo json_encode($player);
  }

  select_game ($db, $request_vars["username"], $request_vars["played"]);
?>
