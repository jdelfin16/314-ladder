<?php
  // Displaying the first part of the page
  include_once 'header.php';
?>
        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Home</h2>
            <h3>Welcome to Justin's World!</h3>
            <p>
              Justin's World is a database where players can view
              matchups and challenge others!
              <br>
              You can check out the current top 10 players in the database below!
            </p>
          </div>
        </section>

        <section class = "main-container">
          <div class="main-wrapper">
            <h3> Top 10 Players </h3>

            <!--Displaying the top 10 players-->
            <?php
              include_once 'database.php';

              $sql = "select name, email, rank, phone, username from player limit 10;";

              $statement = $db->prepare($sql);

              $statement->execute();

              if ($statement->rowCount() > 0)
              {
                $player = $statement->fetchAll(PDO::FETCH_ASSOC);
              }
              else
              {
                $player = [];
              }
              // Note: $player consists of an array of subarrays
                // Player: Main Array --> Subarrays --> player info...
              // echo json_encode($player);

              // Information on the first 10 players
              $position_one_name = $player[0]['name'];
              if (empty($position_one_name))
              {
                $position_one_name = "N/A";
              }
              $position_one_email = $player[0]['email'];
              if (empty($position_one_email))
              {
                $position_one_email = "N/A";
              }
              $position_one_rank = $player[0]['rank'];
              if (empty($position_one_rank))
              {
                $position_one_rank = 1;
              }
              $position_one_phone = $player[0]['phone'];
              if (empty($position_one_phone))
              {
                $position_one_phone = "N/A";
              }
              $position_one_username = $player[0]['username'];
              if (empty($position_one_username))
              {
                $position_one_username = "N/A";
              }

              $position_two_name = $player[1]['name'];
              if (empty($position_two_name))
              {
                $position_two_name = "N/A";
              }
              $position_two_email = $player[1]['email'];
              if (empty($position_two_email))
              {
                $position_two_email = "N/A";
              }
              $position_two_rank = $player[1]['rank'];
              if (empty($position_two_rank))
              {
                $position_two_rank = 2;
              }
              $position_two_phone = $player[1]['phone'];
              if (empty($position_two_phone))
              {
                $position_two_phone = "N/A";
              }
              $position_two_username = $player[1]['username'];
              if (empty($position_two_username))
              {
                $position_two_username = "N/A";
              }

              $position_three_name = $player[2]['name'];
              if (empty($position_three_name))
              {
                $position_three_name = "N/A";
              }
              $position_three_email = $player[2]['email'];
              if (empty($position_three_email))
              {
                $position_three_email = "N/A";
              }
              $position_three_rank = $player[2]['rank'];
              if (empty($position_three_rank))
              {
                $position_three_rank = 3;
              }
              $position_three_phone = $player[2]['phone'];
              if (empty($position_three_phone))
              {
                $position_three_phone = "N/A";
              }
              $position_three_username = $player[2]['username'];
              if (empty($position_three_username))
              {
                $position_three_username = "N/A";
              }

              $position_four_name = $player[3]['name'];
              if (empty($position_four_name))
              {
                $position_four_name = "N/A";
              }
              $position_four_email = $player[3]['email'];
              if (empty($position_four_email))
              {
                $position_four_email = "N/A";
              }
              $position_four_rank = $player[3]['rank'];
              if (empty($position_four_rank))
              {
                $position_four_rank = 4;
              }
              $position_four_phone = $player[3]['phone'];
              if (empty($position_four_phone))
              {
                $position_four_phone = "N/A";
              }
              $position_four_username = $player[3]['username'];
              if (empty($position_four_username))
              {
                $position_four_username = "N/A";
              }

              $position_five_name = $player[4]['name'];
              if (empty($position_five_name))
              {
                $position_five_name = "N/A";
              }
              $position_five_email = $player[4]['email'];
              if (empty($position_five_email))
              {
                $position_five_email = "N/A";
              }
              $position_five_rank = $player[4]['rank'];
              if (empty($position_five_rank))
              {
                $position_five_rank = 5;
              }
              $position_five_phone = $player[4]['phone'];
              if (empty($position_five_phone))
              {
                $position_five_phone = "N/A";
              }
              $position_five_username = $player[4]['username'];
              if (empty($position_five_username))
              {
                $position_five_username = "N/A";
              }

              $position_six_name = $player[5]['name'];
              if (empty($position_six_name))
              {
                $position_six_name = "N/A";
              }
              $position_six_email = $player[5]['email'];
              if (empty($position_six_email))
              {
                $position_six_email = "N/A";
              }
              $position_six_rank = $player[5]['rank'];
              if (empty($position_six_rank))
              {
                $position_six_rank = 6;
              }
              $position_six_phone = $player[5]['phone'];
              if (empty($position_six_phone))
              {
                $position_six_phone = "N/A";
              }
              $position_six_username = $player[5]['username'];
              if (empty($position_six_username))
              {
                $position_six_username = "N/A";
              }

              $position_seven_name = $player[6]['name'];
              if (empty($position_seven_name))
              {
                $position_seven_name = "N/A";
              }
              $position_seven_email = $player[6]['email'];
              if (empty($position_seven_email))
              {
                $position_seven_email = "N/A";
              }
              $position_seven_rank = $player[6]['rank'];
              if (empty($position_seven_rank))
              {
                $position_seven_rank = 7;
              }
              $position_seven_phone = $player[6]['phone'];
              if (empty($position_seven_phone))
              {
                $position_seven_phone = "N/A";
              }
              $position_seven_username = $player[6]['username'];
              if (empty($position_seven_username))
              {
                $position_seven_username = "N/A";
              }

              $position_eight_name = $player[7]['name'];
              if (empty($position_eight_name))
              {
                $position_eight_name = "N/A";
              }
              $position_eight_email = $player[7]['email'];
              if (empty($position_eight_email))
              {
                $position_eight_email = "N/A";
              }
              $position_eight_rank = $player[7]['rank'];
              if (empty($position_eight_rank))
              {
                $position_eight_rank = 8;
              }
              $position_eight_phone = $player[7]['phone'];
              if (empty($position_eight_phone))
              {
                $position_eight_phone = "N/A";
              }
              $position_eight_username = $player[7]['username'];
              if (empty($position_eight_username))
              {
                $position_eight_username = "N/A";
              }

              $position_nine_name = $player[8]['name'];
              if (empty($position_nine_name))
              {
                $position_nine_name = "N/A";
              }
              $position_nine_email = $player[8]['email'];
              if (empty($position_nine_email))
              {
                $position_nine_email = "N/A";
              }
              $position_nine_rank = $player[8]['rank'];
              if (empty($position_nine_rank))
              {
                $position_nine_rank = 9;
              }
              $position_nine_phone = $player[8]['phone'];
              if (empty($position_nine_phone))
              {
                $position_nine_phone = "N/A";
              }
              $position_nine_username = $player[8]['username'];
              if (empty($position_nine_username))
              {
                $position_nine_username = "N/A";
              }

              $position_ten_name = $player[9]['name'];
              if (empty($position_ten_name))
              {
                $position_ten_name = "N/A";
              }
              $position_ten_email = $player[9]['email'];
              if (empty($position_ten_email))
              {
                $position_ten_email = "N/A";
              }
              $position_ten_rank = $player[9]['rank'];
              if (empty($position_ten_rank))
              {
                $position_ten_rank = 10;
              }
              $position_ten_phone = $player[9]['phone'];
              if (empty($position_ten_phone))
              {
                $position_ten_phone = "N/A";
              }
              $position_ten_username = $player[9]['username'];
              if (empty($position_ten_username))
              {
                $position_ten_username = "N/A";
              }

              echo "
              <br>
              <table>
                <tr>
                  <th>Rank</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Phone</th>
                  <th>Email</th>
                </tr>
                <tr>
                  <td>$position_one_rank</td>
                  <td>$position_one_name</td>
                  <td>$position_one_username</td>
                  <td>$position_one_phone</td>
                  <td>$position_one_email</td>
                </tr>
                <tr>
                  <td>$position_two_rank</td>
                  <td>$position_two_name</td>
                  <td>$position_two_username</td>
                  <td>$position_two_phone</td>
                  <td>$position_two_email</td>
                </tr>
                <tr>
                  <td>$position_three_rank</td>
                  <td>$position_three_name</td>
                  <td>$position_three_username</td>
                  <td>$position_three_phone</td>
                  <td>$position_three_email</td>
                </tr>
                <tr>
                  <td>$position_four_rank</td>
                  <td>$position_four_name</td>
                  <td>$position_four_username</td>
                  <td>$position_four_phone</td>
                  <td>$position_four_email</td>
                </tr>
                <tr>
                  <td>$position_five_rank</td>
                  <td>$position_five_name</td>
                  <td>$position_five_username</td>
                  <td>$position_five_phone</td>
                  <td>$position_five_email</td>
                </tr>
                <tr>
                  <td>$position_six_rank</td>
                  <td>$position_six_name</td>
                  <td>$position_six_username</td>
                  <td>$position_six_phone</td>
                  <td>$position_six_email</td>
                </tr>
                <tr>
                  <td>$position_seven_rank</td>
                  <td>$position_seven_name</td>
                  <td>$position_seven_username</td>
                  <td>$position_seven_phone</td>
                  <td>$position_seven_email</td>
                </tr>
                <tr>
                  <td>$position_eight_rank</td>
                  <td>$position_eight_name</td>
                  <td>$position_eight_username</td>
                  <td>$position_eight_phone</td>
                  <td>$position_eight_email</td>
                </tr>
                <tr>
                  <td>$position_nine_rank</td>
                  <td>$position_nine_name</td>
                  <td>$position_nine_username</td>
                  <td>$position_nine_phone</td>
                  <td>$position_nine_email</td>
                </tr>
                <tr>
                  <td>$position_ten_rank</td>
                  <td>$position_ten_name</td>
                  <td>$position_ten_username</td>
                  <td>$position_ten_phone</td>
                  <td>$position_ten_email</td>
                </tr>
              </table>
              ";
            ?>

            <br>
            <br>
            <br>
            <h3>Ladder Options</h3>

            <!--Option to search and challenge others-->
            <?php
              if (isset($_SESSION['u_name']))
              {
                echo '<p>
                        Testing...
                      </p>';
              }
              else
              {
                echo '<p>
                        <b><i>Please log in to check out the options!</i></b>
                      </p>';
              }
            ?>

          </div>
        </section>

        </div> <!--Needed for header-->
      </div> <!--Needed for header-->

<?php
  include_once 'footer.php';
?>
