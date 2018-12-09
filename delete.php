<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Leave The Ladder</h2>
            <p>Fill out this form to remove yourself from the Ladder<p>
            <p><i>* After filling out this form, you will be sent back to the Home page</i></p>
            <form class="signup-form" action="includes/delete.inc.php" method="post">
              <input type="text" name="user" placeholder="Username">
              <button type="submit" name="submit">DELETE</button>
            </form>
            <?php

              $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              if (strpos($url, "exit=empty") == true)
              {
                echo "<p class='error'>
                        Please enter your username!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "exit=invalid_username") == true)
              {
                echo "<p class='error'>
                        Invalid username!
                      </p>
                      <p class = 'error'>
                        Field must be <b>your</b> username!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
            ?>
          </div>
        </section>

<?php
  include_once 'footer.php';
?>
