<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Report Match Game Scores & Update Ladder Rankings</h2>

            <?php
              include_once 'database.php';

              $sql = "select * from game;";
              $statement = $db->prepare($sql);
              $statement->execute();

              if ($statement->rowCount() > 0)
              {
                $game = $statement->fetchAll(PDO::FETCH_ASSOC);
              }
              else
              {
                $game = [];
              }
              $display = "<table>
                            <tr>
                              <th>
                                Winner
                              </th>
                              <th>
                                Loser
                              </th>
                              <th>
                                Date Played
                              </th>
                              <th>
                                Game #
                              </th>
                              <th>
                                Winner's Score
                              </th>
                              <th>
                                Loser's Score
                              </th>
                            </tr>";

              for ($i=0; $i<=$statement->rowCount(); $i++)
              {
                $display = $display.
                            "<tr>".
                              "<td>".$game[$i]['winner']."</td>".
                              "<td>".$game[$i]['loser']."</td>".
                              "<td>".$game[$i]['played']."</td>".
                              "<td>".$game[$i]['number']."</td>".
                              "<td>".$game[$i]['winner_score']."</td>".
                              "<td>".$game[$i]['loser_score']."</td>".
                            "</tr>";
              }
              $display = $display."</table>";
              echo $display;

            ?>

            <br>

            <p>Fill out this form to:<p>
            <ol>
              <li>
                To input the results of a game,
              </li>
              <li>
                Update the Ladder ranking based on the results of the game.
              </li>
            </ol>
            <ul>
              <li>
                "Played" should be be formatted as
                "YYYY-MM-DD" along with a 24-hour time (ex. 2015-09-28 18:00:00).
              </li>
            </ul>
            <form class="signup-form" action="includes/game.inc.php" method="post">
              <input type="text" name="winner" placeholder="Winner">
              <input type="text" name="loser" placeholder="Loser">
              <input type="text" name="played" placeholder="Played">
              <input type="text" name="winner_score" placeholder="Winner Score">
              <input type="text" name="loser_score" placeholder="Loser Score">
              <button type="submit" name="submit">Enter Game</button>
            </form>
            <?php

              $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
              if (strpos($url, "game=empty") == true)
              {
                echo "<p class='error'>
                        You did not fill in all the fields!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=invalid_players") == true)
              {
                echo "<p class='error'>
                        The first and/or last names are not formatted properly!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=no_challenge") == true)
              {
                echo "<p class='error'>
                        The challenge between the players does not exist!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=incorrect_scoring") == true)
              {
                echo "<p class='error'>
                        Winner's score is less than the loser's score...
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=challenge_unaccepted") == true)
              {
                echo "<p class='error'>
                        This challenge has not been accepted yet by the challengee!
                      </p>
                      </div>
                    </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=invalid_played") == true)
              {
                echo "<p class='error'>
                        You entered an invalid played date!
                      </p>
                      <p class = 'error'>
                        The 'played' must be in ISO 8601 format as mentioned above!
                      </p>
                    </div>
                  </section>";
                include_once 'footer.php';
                exit();
              }
              elseif (strpos($url, "game=success") == true)
              {
                echo "<p class='success'>
                        Report complete!
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
