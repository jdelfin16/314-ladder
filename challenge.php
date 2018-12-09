<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Challenge Other Players</h2>
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

            <p>Fill out the form below to set a challenge against another player!</p>
            <p>Challenge Rules:</p>
            <ol>
              <li>
                Challengers can challenge those that are at most 3 ranks above them.
              </li>
              <br>
              <li>
                Challengers CANNOT challenge those
                under their current ranks - <i>Why challenge lower rank?</i>
              </li>
              <br>
              <li>
                Challengers CANNOT challenge those that have already accepted another challenge.
              </li>
            </ol>
            <br>
            <ul>
              <li> "Scheduled" should be be formatted as
              "YYYY-MM-DD" along with a 24-hour time (ex. 2015-09-28 18:00:00).</li>
            </ul>
            <form class="signup-form" action="includes/challenge.inc.php" method="post">
              <input type="text" name="challenger" value="<?php echo $_SESSION['u_username']; ?>" placeholder="Username of Challenger">
              <input type="text" name="challengee" placeholder="Username of Challengee">
              <input type="text" name="scheduled" placeholder="Scheduled">
              <button type="submit" name="submit">Send Challenge</button>
            </form>
            <?php

              $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              if (strpos($url, "challenge=empty") == true)
              {
                echo "<p class='error'>
                        You did not fill in all the fields!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=invalid_players") == true)
              {
                echo "<p class='error'>
                        The players are not valid!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=invalid_challenger") == true)
              {
                $name = $_SESSION['u_name'];
                echo "<p class='error'>
                        Only the current challenger (i.e., <b>$name</b>) can set a challenge!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=invalid_scheduled") == true)
              {
                echo "<p class='error'>
                        You entered an invalid schedule!
                      </p>
                      <p class = 'error'>
                        The schedule format must be in ISO 8601 format as mentioned above!
                      </p>
                      <p class = 'error'>
                        The schedule input must also be set after today's date!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=already_exists") == true)
              {
                echo "<p class='error'>
                        The challenge already exist!
                      </p>
                      <p class = 'error'>
                        If your would like to update your existing challenge, please
                        go <a style='color: red;' href='accept_reject.php'><b><u>here!</u></b></a>
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=conflict_challenge") == true)
              {
                echo "<p class='error'>
                        The challengee already accepted another challenge!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=rules__ranks_unfulfilled") == true)
              {
                echo "<p class='error'>
                        The rules are being violated!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "challenge=success") == true)
              {
                echo "<p class='success'>
                        Challenge sent!
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
