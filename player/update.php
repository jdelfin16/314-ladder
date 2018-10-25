<?php
  /*
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;
  */

  // Put/Update player information
  function update_player (/*$connection, $player_data*/)
  {
    echo "update_player check!";
    /*$sql = "update player set ? where name = :name;";
    $statement = $connection->prepare ($sql);
    $statement->execute ($player_data);
    return $statement->rowCount () == 1;*/
  }


?>
