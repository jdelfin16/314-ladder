<?php


  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

  $response = $request_vars;
  //$response["service"] = "player";
  //$response["method"] = $method;


  function insert_player ($connection, $player_data)
  {
    echo "testing insert_player!";
    /*$sql = "insert into player (name, username, rank, email, phone, password, values)
    values (:name, :username, :rank, :email, :phone, :password);";

    $statement = $connection->prepare ($sql);
    $statement->execute ($player_data);
    return $statement->rowCount () == 1;
    */
  }

  insert_player();
?>
