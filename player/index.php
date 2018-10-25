<?php

	include "../database.php";
	include "../rest.php";
	include "insert.php";
	include "select.php";
	include "delete.php";
	include "update.php";

	$request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;


	//$echo json_encode($response);


	/*---------------------------------------------------------
	$sql = "select * from player where username = ?";

	$statement = $db->prepare($sql);

	$username = "bcoleman0";

	#run the query
	$statement->execute([$username]);

	#get the results
	$results = $statement->fetchAll(PDO::FETCH_ASSOC);

	#show the number of rows effected
	echo "Rows: " . $statement->rowCount() . "<br />";

	foreach($results as $row)
	{
		$username = $row["username"];
		$rank = $row["rank"];
		$name = $row["name"];

		echo "$name, <br /> $username, <br /> $rank <br />";
	}
	// ----------------------------------------------------------
	*/

	// Return the number of players in the database
	function num_of_players ($connection)
	{
		$sql = "select * from player;";
		$statement = $connection->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}

	echo "Hello World!";
	echo "<br> <br>";

	echo "Testing Insert Player: ";
	insert_player();
	echo "<br>";
	/*Find the new rank, show the number of players
	$request_vars["rank"] = num_of_players ($db) + 1;
	echo "Number of Players: " . $request_vars["rank"] . "<br />";

	foreach ($request_vars as $var => $value)
	{
		echo "Variable: " . $var . "<br> Value: " . $value . "<br />";
	}

	// New player has been inserted/added
	$success = insert_player ($db, $request_vars);
	echo "Success: " . $success;
	*/
	echo "<br> <br>";
	echo "Testing Select Player: ";
	select_player();
	echo "<br>";
	echo "Testing Update Player: ";
	update_player();
	echo "<br>";
	echo "Testing Delete Player: ";
	delete_player();
	echo "<br>";
	

?>
