<?php
  /*
    Create a new player. The player's rank should be automatically be
    assigned the highest value not yet taken.
      - Based on the number of players...
  */
  function insert_player($connection, $name, $email, $phone, $username, $password)
  {
    // Acquires the number of players in the database
    $sql_select = "select * from player;";
    $statement_select = $connection->prepare($sql_select);
    $statement_select->execute();
    $result_select = $statement_select->rowCount();

    // Create rank parameter for inserting...
    $player_rank = $result_select + 1;

    // Create SQL
    $sql = "insert into player (name, email, rank, phone, username, password)
      values (:name, :email, :rank, :phone, :username, :password);";

    // Preparing query
    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':name', $name);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':rank', $player_rank);
    $statement->bindParam(':phone', $phone);
    $statement->bindParam(':username', $username);
    $statement->bindParam(':password', $password);

    $statement->execute ();
    $result = $statement->rowCount() == 1;

    echo json_encode($result);
    // echo json_encode(intval($player_rank));
  }
?>
