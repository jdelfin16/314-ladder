<?php

	include "../rest.php";
	include "../database.php";
	
	$request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

    $response = $request_vars;
    $response["service"] = "player";
    $response["method"] = $method;
	$echo json_encode($response);
	
	// Return the number of players in the database
	function num_of_players ($connection)
	{
		$sql = "select * from player;";
		$statement = $connection->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}
	
	// Create/Insert a new player
	function insert_player ($connection, $player_data)
	{
		$sql = "insert into player (name, username, rank, email, phone, password, values) 
		values (:name, :username, :rank, :email, :phone, :password);";
		
		$statement = $connection->prepare ($sql);
		$statement->execute ($player_data);
		return $statement->rowCount () == 1;
	}
	
	//Get/Select player information
	function select_player ($connection, $player_data)
	{
		$sql = "select * from player;";
		$statement = $connection->prepare ($sql);
		$statement->execute ($player_data);
		return $statement->rowCount () == 1;
	}
	
	// Put/Update player information
	function update_player ($connection, $player_data)
	{
		$sql = "update player set ? where name = :name;";
		$statement = $connection->prepare ($sql);
		$statement->execute ($player_data);
		return $statement->rowCount () == 1;
	}
	
	// Delete player
	function delete_player ($connection, $player_data)
	{
		$sql = "delete from player where name = ?;";
		$statement = $connection->prepare ($sql);
		$statement->execute ($player_data);
		return $statement->rowCount () == 1;
	}
	
	// Find the new rank, show the number of players
	$data["rank"] = num_of_players ($db) + 1;
	echo "Number of Players" . $data["rank"] . "<br />";
	
	foreach ($data as $var => $value)
	{
		echo "Variable: " . $var . "Value: " . $value . "<br />";
	}
	
	// New player has been inserted/added
	$success = insert_player ($db, $data);
	echo "Success: " . $success;
	
	echo "Hello World!";
?>

