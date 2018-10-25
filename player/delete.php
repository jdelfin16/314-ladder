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

  // Delete player
  function delete_player (/*$connection, $player_data*/)
  {
    echo "delete_player check!";
    /*$sql = "delete from player where name = ?;";
    $statement = $connection->prepare ($sql);
    $statement->execute ($player_data);
    return $statement->rowCount () == 1;*/
  }


?>
