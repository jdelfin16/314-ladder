<?php
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title> Justin's World | CSIS 314 - A </title>
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel = "stylesheet" type = "text/css" href = "https://www.w3schools.com/w3css/4/w3.css" />
    <link rel = "stylesheet" type = "text/css" href = "indexCSS.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body>
    <div class = "container">
      <div class = "content">
        <header>
       		<h1 class = "center w3-text-black">
            <b> Justin's World </b>
          </h1>
    		  <h2 class = "center w3-text-black" >
            <a href = "http://jorr.cs.georgefox.edu/courses/csis314-client-server">
              <b> CSIS 314 - A: Client-Server Systems <br/> (<u>Click to check out the class!</u>)</b>
            </a>
          </h2>
          <br>
          <br>
          <nav>
            <div class="main-wrapper">
              <ul>

                <!--Displaying the user's name when signed in-->
                <?php
                  if (isset($_SESSION['u_name']))
                  {
                    $session_name = $_SESSION['u_name'];
                    echo "<li style='font-family: arial; font-size: 18px;
                      color: #111; line-height: 63px;
                      font-weight: bold;'>Welcome, $session_name!</li>";
                  }
                ?>

                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="all.php">Player Table</a></li>


              </ul>
              <div class="nav-login">

                <!--Displaying the logout option if valid; otherwise, display the
                option to log in-->
                <?php
                  if (isset($_SESSION['u_name']))
                  {

                    echo '<form action="logout/index.php" method="post">
                            <button type="submit" name="submit">Logout</button>
                          </form>';
                  }
                  else
                  {
                    echo '<form action="login/index.php" method="post">
                            <input type="text" name="uid" placeholder="Username">
                            <input type="password" name="pwd" placeholder="Password">
                            <button type="submit" name="submit">Login</button>
                          </form>
                          <a href="signup.php">Sign Up</a>';
                  }
                ?>

              </div>
            </div>
          </nav>
        </header>
      <!--Rest of the code will be in in the other pages and footer.php-->
