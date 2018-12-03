<?php
  session_start();

  if (isset($_POST['submit']))
  {
    include 'database.php';

    // Add security to these variables later...
    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    // Error handlers
    // Check if inputs are empty
    if (empty($username) || empty($password))
    {
      header("Location: ../index.php?login=empty");
      exit();
    }
    else
    {
      $sql = "select * from player where username = :username";
      $statement = $db->prepare($sql);
      $statement->bindParam(':username', $username);
      $statement->execute();
      $result = $statement->rowCount();
      if ($result < 1)
      {
        header("Location: ../index.php?login=invalid_input");
        exit();
      }
      else
      {
        if ($statement->rowCount() >= 1)
        {
          $row = $statement->fetch(PDO::FETCH_ASSOC);

          // De-hashing the password
          // $dehashed = password_verify($password, $row['password']);

          // TESTING - Checking the password
          // if ($dehashed == false)
          if ($password != $row['password'])
          {
            header("Location: ../index.php?login=invalid_input");
            exit();
          }
          // elseif ($dehashed == true)
          elseif ($password == $row['password'])
          {
            // Login in the user here
            $_SESSION['u_name'] = $row['name'];
            $_SESSION['u_username'] = $row['username'];
            $_SESSION['u_email'] = $row['email'];
            header("Location: ../index.php?login=success");
            exit();
          }
        }
      }
    }

  }
  else
  {
    header("Location: ../index.php?login=error");
    exit();
  }
?>
