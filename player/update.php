<?php
  include "../database.php";
  include "../rest.php";

  $request = new RestRequest();
  $method = $request->getRequestType();
  $request_vars = $request->getRequestVariables();

  $response = $request_vars;
  $response["service"] = "player";
  $response["method"] = $method;

  // Arrays for valid_keys() function - based on methods
  $UPDATE_CHECK = array("username", "name", "email", "rank", "phone");

  // Values
  $name = $response["name"];
  $email = $response["email"];
  $phone = $response["phone"];
  $username = $response["username"];
  $rank = $response["rank"];

  /*
    Updates the player with the given parameters. All the players between the
    old and new rank need to be shifted either up or down depending on the change.
      - Hint: username is the only unupdatable parameter?
  */
  function update_player($connection, $username, $name, $email, $rank, $phone)
  {
    $sql = "update player set name = :name, email = :email, rank = :rank,
      phone = :phone where username = :username;";

    // Create if-statements if there are empty parameters?
      // ...if there is nothing to update?
      // TESTED: if the parameter is empty, the player will be updated with null...

    $statement = $connection->prepare($sql);

    // Binding parameters
    $statement->bindParam(':username', $username);
    $statement->bindParam(':name', $name);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':rank', $rank);
    $statement->bindParam(':phone', $phone);

    $statement->execute ();
    $result = $statement->rowCount () == 1;

    echo json_encode($result);
    }
  }
?>
