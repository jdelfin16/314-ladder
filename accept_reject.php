<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Accept/Reject Challenges</h2>
            <?php
              include_once 'database.php';

              $sql = "select challenger, challengee, issued, accepted, scheduled
                from challenge;";
              $statement = $db->prepare($sql);
              $statement->execute();

              if ($statement->rowCount() > 0)
              {
                $challenge = $statement->fetchAll(PDO::FETCH_ASSOC);
              }
              else
              {
                $challenge = [];
              }
              $display = "<table>
                            <tr>
                              <th>
                                Challenger
                              </th>
                              <th>
                                Challengee
                              </th>
                              <th>
                                Date Issued
                              </th>
                              <th>
                                Date Accepted
                              </th>
                              <th>
                                Date Scheduled
                              </th>
                            </tr>";

              for ($i=0; $i<=$statement->rowCount(); $i++)
              {
                $display = $display.
                            "<tr>".
                              "<td>".$challenge[$i]['challenger']."</td>".
                              "<td>".$challenge[$i]['challengee']."</td>".
                              "<td>".$challenge[$i]['issued']."</td>".
                              "<td>".$challenge[$i]['accepted']."</td>".
                              "<td>".$challenge[$i]['scheduled']."</td>".
                            "</tr>";
              }
              $display = $display."</table>";
              echo $display;

            ?>

            <br>

            <p>Fill out this form to accept or reject challenges<p>
            <p>Formatting:</p>
            <ol>
              <li>
                The "Scheduled" and "Accept/Reject" text boxes must be formated in ISO 8601 format.
                <ul>
                  <br>
                  <li>
                    "Scheduled" should be be formatted as
                    "YYYY-MM-DD" along with a 24-hour time (ex. 2015-09-28 18:00:00).
                  </li>
                </ul>
              </li>
              <br>
              <li>
                Challengers CANNOT challenge those that have already accepted another challenge.
              </li>
            </ol>
            <form class="signup-form" action="includes/accept_reject.inc.php" method="post">
              <input type="text" name="challenger" placeholder="Challenger">
              <input type="text" name="challengee" value="<?php echo $_SESSION['u_username']; ?>" placeholder="Challengee">
              <input type="text" name="scheduled" placeholder="Scheduled">

              <input type="radio" name="choice" value="accept">
              <label for="accept">Accept</label>

              <input type="radio" name="choice" value="reject">
              <label for="reject">Reject</label>
              <br>

              <button type="submit" name="submit">Send Choice</button>
            </form>
            <?php

              $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              if (strpos($url, "choice=empty") == true)
              {
                echo "<p class='error'>
                        You did not fill in/select all the fields!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=invalid_players") == true)
              {
                echo "<p class='error'>
                        The players are not valid!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=no_challenge") == true)
              {
                echo "<p class='error'>
                        This challenge does not exist!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=invalid_scheduled") == true)
              {
                echo "<p class='error'>
                        You entered an invalid schedule!
                      </p>
                      <p class = 'error'>
                        The schedule format must be in ISO 8601 format!
                      </p>
                      <p class = 'error'>
                        The schedule input must also be set after today's date!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=challengee_only") == true)
              {
                $name = $_SESSION['u_name'];
                echo "<p class='error'>
                        Only the challengee (i.e., <b>name</b>) can accept this challenge!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=accepted") == true)
              {
                echo "<p class='success'>
                        Challenge accepted!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "choice=rejected") == true)
              {
                echo "<p class='success'>
                        Challenge rejected!
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
