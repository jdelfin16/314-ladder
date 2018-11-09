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
    else {
      $game = [];
    }
    echo json_encode($game);
  }

  // select_game ($db, $request_vars["username"], $request_vars["played"]);
?>
