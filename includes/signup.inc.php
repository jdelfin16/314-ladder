<?php
  if (isset($_POST['submit']))
  {
    include_once 'database.php';

    $first = $_POST['first'];
    $last = $_POST['last'];

    // Values needed for the player table
    $name = "$first $last";
    $email = $_POST['email'];
    $username = $_POST['uid'];
    $phone = $_POST['phone'];
    $password = $_POST['pwd'];

    // Error handlers
    // Check for empty fields...
    if (empty($first) || empty($last) || empty($email) || empty($username) || empty($password) || empty($phone))
    {
      header("Location: ../signup.php?signup=empty");
      exit();
    }
    else
    {
      // Check if input characters are valid
      if (!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last))
      {
        header("Location: ../signup.php?signup=invalid_names");
        exit();
      }
      else
      {
        // Check if email is valid.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
          header("Location: ../signup.php?signup=invalid_email");
          exit();
        }
        else
        {
          $sql = "select * from player where username = :username";
          $statement = $db->prepare($sql);
          $statement->bindParam(':username', $username);
          $statement->execute();
          $result = $statement->rowCount();
          if ($result > 0)
          {
            header("Location: ../signup.php?signup=username_exists");
            exit();
          }
          else
          {

            if (!preg_match('/^[0-9]{3}[0-9]{3}[0-9]{4}$/',$phone, $matches))
            {
              header("Location: ../signup.php?signup=invalid_phone_number");
              exit();
            }
            else
            {
              // Hashing the password
              $hashed = password_hash($password, PASSWORD_DEFAULT);

              // Insert user into database

              // Acquires the number of players in the database
              $sql_select = "select * from player;";
              $statement_select = $db->prepare($sql_select);
              $statement_select->execute();
              $result_select = $statement_select->rowCount();

              // Create rank parameter for inserting...
              $player_rank = $result_select + 1;

              // Create SQL
              $sql = "insert into player (name, email, rank, phone, username, password)
                values (:name, :email, :rank, :phone, :username, :password);";

              // Preparing query
              $statement = $db->prepare($sql);

              // Binding parameters
              $statement->bindParam(':name', $name);
              $statement->bindParam(':email', $email);
              $statement->bindParam(':rank', $player_rank);
              $statement->bindParam(':phone', $phone);
              $statement->bindParam(':username', $username);
              // $statement->bindParam(':password', $hashed);
              $statement->bindParam(':password', $password); // Testing without hashing

              $statement->execute ();
              header("Location: ../signup.php?signup=success");
              exit();
            }
          }
        }
      }
    }
  }
  else
  {
    header("Location: ../signup.php");
    exit();
  }
