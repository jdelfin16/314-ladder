<?php
  function delete_player($connection, $username)
  {
    $sql = "delete from player where username = :username;";

    $statement = $connection->prepare($sql);

    $statement->bindParam(':username', $username);

    $statement->execute();

    // Return / display results
    $result = $statement->rowCount () == 1;
    echo json_encode($result);
  }
?>
