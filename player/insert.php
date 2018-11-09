<?php

  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;


  // Return the number of players in the database
	function num_of_players ($connection)
	{
		$sql = "select * from player;";
		$statement = $connection->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}

  function insert_player ($connection, $player_data)
  {
    // $player_data represents the rank data
    $sql = "insert into player (name, username, rank, email, phone, password)
			values (:name, :username, :rank, :email, :phone, :password);";
		$statement = $connection->prepare ($sql);

    $name = $_POST["name"];
    $username = $_POST["username"];
    $rank = $player_data;
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];

    $statement->bindParam('ssisis', $name, $username, $rank,
     $email, $phone, $password);

    $statement->execute ($player_data);
		return $statement->rowCount () == 1;
  }
  // Note: the code below is in index.php

  // Find the new rank, show the number of players
	$request_vars["rank"] = num_of_players ($db) + 1;

	// New player has been inserted/added
  echo json_encode(insert_player($db, $request_vars));
?>
