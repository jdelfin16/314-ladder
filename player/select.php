<?php
  /*
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  */
  //$response["service"] = "player";
  //$response["method"] = $method;

  //Get/Select player information
  function select_player ($connection, $username)
  {
    // Query
    $sql = "select username, name, phone, email, rank from player
     where username = ?;";

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

    return ["player"=>$player];
  }

  /*$test = "rmills1";
  select_player($db, $test);
  */
?>
