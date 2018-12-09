<?php
  include_once 'header.php';
?>

        <section class = "main-container">
          <div class="main-wrapper">
            <h2>Player Table</h2>
            <p>Below is the table of all the players in the Ladder!</p>
            <br>
            <?php
              include_once 'database.php';

              $sql = "select * from player order by rank;";
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
              $display = "<table>
                            <tr>
                              <th>
                                Rank
                              </th>
                              <th>
                                Name
                              </th>
                              <th>
                                Username
                              </th>
                              <th>
                                Phone
                              </th>
                              <th>
                                Email
                              </th>
                            </tr>";

              for ($i=0; $i<=$statement->rowCount(); $i++)
              {
                $display = $display.
                            "<tr>".
                              "<td>".$player[$i]['rank']."</td>".
                              "<td>".$player[$i]['name']."</td>".
                              "<td>".$player[$i]['username']."</td>".
                              "<td>".$player[$i]['phone']."</td>".
                              "<td>".$player[$i]['email']."</td>".
                            "</tr>";
              }
              $display = $display."</table>";
              echo $display;

            ?>

          </div>
        </section>

<?php
  include_once 'footer.php';
?>
