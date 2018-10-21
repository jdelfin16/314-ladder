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
		$sql = "select * from player";
		$statement = $connection->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}
	
	// Create/Insert a new player
	function insert_player ($connection, $player_data)
	{
		/*$sql = "insert into player (name, username, rank, email, phone, password, values)
			values (:name, :username, :rank, :email, :phone, :password)";
		$statement = $connection->prepare ($sql);
		$statement->execute ($player_data);
		return $statement->rowCount () == 1;
		*/
	}
	
	//Get/Select player information
	function select_player ()
	{
		$sql = "select * from player";
		
	}
	
	// Put/Update player information
	function update_player ()
	{
		$sql = "update player set ___ where name = :name";
	}
	
	// Delete player
	function delete_player ()
	{
		$sql = "delete from player where name = :name";
	}
	
	// Find the new rank, show the number of players
	$data["rank"] = num_of_players ($db) + 1;
	echo "Number of Players" . $data["rank"] . "<br />";
	
	foreach ($data as $var => $value)
	{
		echo "variable: " . $var . "value: " . $value . "<br />";
	}
	
	// New player has been inserted/added
	$success = insert_player ($db, $data);
	echo "success: " . $success;
	
?>

