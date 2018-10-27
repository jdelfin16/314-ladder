<?php

  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  //$response["service"] = "player";
  //$response["method"] = $method;


  // Put/Update player information
  function update_player ($connection, $player_data)
  {
    //echo "update_player check!";
    $sql = "update player set name = :name, username = :username,
      password = :password, email = :email, phone = :phone,
        where username = :username;";

    // $player_data = data for $rank

    $statement = $connection->prepare ($sql);

    $name = ;
    $newUsername = ;
    $password = ;
    $email = ;
    $phone = ;
    $oldUsername = ;

    $statement->execute ($player_data);
    return $statement->rowCount () == 1;
  }
  update_player();

?>
