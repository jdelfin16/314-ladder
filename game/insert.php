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

  // Assigning the 6 values to the parameters

  ---------------------Note: Fix number later------------------------------
  */
  // $array contains the first 6 values needed
  $insert_array = array();
  foreach ($response as $value)
  {
    $insert_array[] = $value;
  }
  // Positions [6] and [7] are not what I need

  $winner = $insert_array[0];
  $loser = $insert_array[1];
  $played = $insert_array[2];
  $number = $insert_array[3];
  $winner_score= $insert_array[4];
  $loser_score= $insert_array[5];

  function insert_game ($connection, $winner, $loser, $played, $number,
    $winner_score, $loser_score)
  {
    // Create SQL
    $sql = "insert into game (winner, loser, played, number, winner_score, loser_score)
    values (:winner, :loser, :played, :number, :winner_score, :loser_score);";

    // Preparing query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':winner', $winner);
    $statement->bindParam(':loser', $loser);
    $statement->bindParam(':played', $played);
    $statement->bindParam(':number', $number);
    $statement->bindParam(':winner_score', $winner_score);
    $statement->bindParam(':loser_score', $loser_score);

    $statement->execute ();
    $result = $statement->rowCount () == 1;
    echo json_encode($result);
  }

  // Testing:
  // insert_game($db, $winner, $loser, $played, $number, $winner_score, $loser_score);

?>
