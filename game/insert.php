<?php
  /*
  include "../database.php";
  include "../rest.php";
  include "checking.php";

  $request = new RestRequest();
	$method = $request->getRequestType();
	$request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "game";
  $response["method"] = $method;

  // Values for POST and DELETE
  $winner = $response["winner"];
  $loser = $response["loser"];
  $played = $response["played"];
  $number = $response["number"];
  $winner_score= $response["winner_score"];
  $loser_score= $response["loser_score"];


  ALLOW THE FUNCTION TO BE ADAPTABLE TO MULTIPLE ARRAYS VS. SINGLE ARRAY
  */
  function insert_game ($connection, $winner, $loser, $played, $winner_score, $loser_score)
  {
    // Fill in $number parameter
    $number = number_parameter($connection, $winner, $loser);

    // Quit if $number parameter is false
    if ($number == false)
    {
      echo json_encode("ERROR: INVALID PLAYERS!");
    }
    else
    {
      // Create SQL
      $sql = "insert into game (winner, loser, played, number, winner_score,
        loser_score) values (:winner, :loser, :played, :number,
        :winner_score, :loser_score);";

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
      echo "Game added! <br />";
      echo json_encode($result);
    }
  }

  // insert_game($db, $winner, $loser, $played, $winner_score, $loser_score);
?>
