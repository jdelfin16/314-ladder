<?php
  // Function selecting the player by the username
  function select_player($connection, $username)
  {
    $sql = "select name, email, rank, phone, username from player where username = :username;";

    $statement = $connection->prepare($sql);

    $statement->bindParam(":username", $username);

    $statement->execute();

    if ($statement->rowCount() == 1)
    {
      $player = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
      $player = [];
    }
    echo json_encode($player);
  }
?>
