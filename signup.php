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
              <input id="phonenum" type="tel" name="phone" placeholder="Phone Number">
              <input type="text" name="pwd" placeholder="Password">
              <button type="submit" name="submit">Sign Up</button>
            </form>
          </div>
        </section>
        </div>
      </div>

<?php
  include_once 'footer.php';
?>
