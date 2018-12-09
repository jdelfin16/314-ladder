<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Sign Up</h2>
            <form class="signup-form" action="includes/signup.inc.php" method="post">
              <input type="text" name="first" placeholder="Firstname">
              <input type="text" name="last" placeholder="Lastname">
              <input type="text" name="email" placeholder="Email">
              <input type="text" name="uid" placeholder="Username">
              <input type="tel" name="phone" placeholder="Phone Number">
              <input type="password" name="pwd" placeholder="Password">
              <button type="submit" name="submit">Sign Up</button>
            </form>
            <?php

              $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              if (strpos($url, "signup=empty") == true)
              {
                echo "<p class='error'>
                        You did not fill in all the fields!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "signup=invalid_names") == true)
              {
                echo "<p class='error'>
                        The first and/or last names are not formatted properly!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "signup=invalid_email") == true)
              {
                echo "<p class='error'>
                        You entered an invalid email!!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "signup=username_exists") == true)
              {
                echo "<p class='error'>
                        The username is already used!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "signup=invalid_phone_number") == true)
              {
                echo "<p class='error'>
                        You entered an invalid phone number!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "signup=success") == true)
              {
                echo "<p class='success'>
                        Sign up complete!!!
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
