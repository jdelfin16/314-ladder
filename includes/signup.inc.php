<?php
  // If the submit button was pressed...
  if (isset($_POST['submit']))
  {
    include_once 'database.php';

    // Aqcuire variables from $_POST
    $first = $_POST['first'];
    $last = $_POST['last'];

    // Acquire values needed - in this case, for inserting into the player table
    $name = "$first $last";
    $email = $_POST['email'];
    $username = $_POST['uid'];
    $phone = $_POST['phone'];
    $password = $_POST['pwd'];

    // Error handlers
      // Start by checking for bad info...

    // Check for empty fields...
    if (empty($first) || empty($last) || empty($email) || empty($username) || empty($password) || empty($phone))
    {
      // Message saying "Empty Parameters"
      header("Location: ../signup.php?signup=empty");
      exit();
    }
    // If fields are not empty, validate the others...
    else
    {
      // Check if the name are valid
      if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last))
      {
        // Message saying "Improper names"
        header("Location: ../signup.php?signup=invalid_names");
        exit();
      }
      // If the names are proper, validate the others...
      else
      {
        // Check if email is valid.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          // Message saying "Improper email"
          header("Location: ../signup.php?signup=invalid_email");
          exit();
        }
        // If the email is proper, validate the others...
        else
        {
          // Check if the username already exists in the database.
          $sql = "select * from player where username = :username";
          $statement = $db->prepare($sql);
          $statement->bindParam(':username', $username);
          $statement->execute();
          $result = $statement->rowCount();
          if ($result > 0)
          {
            // Message saying "Username already exists"
            header("Location: ../signup.php?signup=username_exists");
            exit();
          }
          // If the username does not exist already, validate the others...
          else
          {
            // Check if the phone number is valid
            if (!preg_match('/^[0-9]{3}[0-9]{3}[0-9]{4}$/',$phone, $matches))
            {
              // Message saying "Phone number is invalid"
              header("Location: ../signup.php?signup=invalid_phone_number");
              exit();
            }
            // If the phone number is valid, proceed to insert the data
              // No need to validate the password...
            else
            {
              // Hashing the password - May need to reset the original database first...
              $hashed = password_hash($password, PASSWORD_DEFAULT);

              // After everything is validated, insert user into database

              // Acquires the number of players in the database
              $sql_select = "select * from player;";
              $statement_select = $db->prepare($sql_select);
              $statement_select->execute();
              $result_select = $statement_select->rowCount();

              // Create rank parameter for inserting...
                // Add person to bottom of the list...
              $player_rank = $result_select + 1;

              // Create SQL
              $sql = "insert into player (name, email, rank, phone, username, password)
                values (:name, :email, :rank, :phone, :username, :password);";
              $statement = $db->prepare($sql);
              $statement->bindParam(':name', $name);
              $statement->bindParam(':email', $email);
              $statement->bindParam(':rank', $player_rank);
              $statement->bindParam(':phone', $phone);
              $statement->bindParam(':username', $username);
              // $statement->bindParam(':password', $hashed);
              $statement->bindParam(':password', $password); // Testing without hashing

              // INSERT!!!
              $statement->execute ();

              // After inserting, show that it was successful!
              header("Location: ../signup.php?signup=success");
              exit();
            }
          }
        }
      }
    }
  }
  // If the submit button was not pressed, send user to the sign up page
  else
  {
    header("Location: ../signup.php");
    exit();
  }
