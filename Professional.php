<?php
$connection = mysqli_connect("localhost", "root", "", "fitnessTracker");
if ($connection->connect_error)
  {
    die();
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
  // for test
  $username = TomTrainer2022;
  $q = "SELECT * FROM Appointments WHERE Professional_Username = '".$username."'";

  // get next appointment
  $appoints = $connection -> query($q);
  $row = mysqli_fetch_array($appoints);
  $c_date = date("Y/m/d/h");
  echo $c_date;
  while($row = mysqli_fetch_array($appoints)){
    if($row['Date']."/".$row['Time'] > $cdate){
      $date = $row['Date'];
      $client = $row['Client_Username'];
      $time = $row['Time'];
      if($time < 12)
        $time = $time ."AM";
      else if($time == 12){
        $time = $time ."PM";
      } else {
        $time -= 12;
        $time = $time . "PM";
      }
      break;
    }
  }

  // get all clients of professional
  $q = "SELECT Client_Username FROM Hires where Professional_Username = '".$username."'";
  $clients = mysqli_query($connection, $q);
  for($i = 0; $i < $clients -> num_rows; $i++){
    $row = mysqli_fetch_array($clients);
    $clients_arr[$i] = $row['Client_Username'];
  }
  echo "Returned rows are: " . $clients -> num_rows;
    // Free result set
  //$test -> free_result();
  
?>
<!DOCTYPE html>
<html lang = "en-US">
    <head>
        <link href = "./style.css?ver=1" rel = "stylesheet">
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>Fitness Tracker</title>
    </head>
    <body>
      <style>
            table, th, td {
              border: 1px solid black;
            }
          </style>
        <div id = "username">
          <h1><?php echo $username ?></h1>
        </div> 
        <div style = "text-align: right">
          <button id = "editProfile"> <a href = "editProfile.php"> Edit Profile </a> </button>
          <button id = "logOut"> <a href = "logOut.php"> LogOut </a> </button>
        </div>
        <div>
          <?php
          echo "Your next appointment is on " .$date. " with " .$client. " at ". $time;
          ?>
        </div>
        <div id = "clients">
          <table style = "float: right;" WIDTH = 200 bordercolor = black>
            <tr>
              <th>Clients</th>
            </tr>
            <?php
              for($i = 0; $i < count($clients_arr); $i++){
                echo "<tr>";
                echo "<td>" . $clients_arr[$i] . "</td>";
                echo "</tr>";
              }
            ?>
          </table>
        </div>
        <div class = "cal">
          <div class = "month">
            <ul>
              <div class = "previous"> < </div>
              <h2 id = cm></h2>
              <div class = "next"> > </div>
            </ul>
          </div>
          <div class = "week">
            <ul>
              <li> Mon </li>
              <li> Tue </li>
              <li> Wed </li>
              <li> Thu </li>
              <li> Fri </li>
              <li> Sat </li>
              <li> Sun </li>
            </ul>
          </div>
          <div class = "day">
            <div></div>
            <div></div>
            <div></div>
            <div id = 1>1</div>
            <div id = 2>2</div>
            <div id = 3>3</div>
            <div id = 4>4</div>
            <div id = 5>5</div>
            <div id = 6>6</div>
            <div id = 7>7</div>
            <div id = 8>8</div>
            <div id = cd>9</div>
            <div id = 10>10</div>
            <div id = 11>11</div>
            <div id = 12>12</div>
            <div id = 13>13</div>
            <div id = 14>14</div>
            <div id = 15>15</div>
            <div id = 16>16</div>
            <div id = 17>17</div>
            <div id = 18>18</div>
            <div id = 19>19</div>
            <div id = 20>20</div>
            <div id = 21>21</div>
            <div id = 22>22</div>
            <div id = 23>23</div>
            <div id = 24>24</div>
            <div id = 25>25</div>
            <div id = 26>26</div>
            <div id = 27>27</div>
            <div id = 28>28</div>
            <div id = 29>29</div>
            <div id = 30>30</div>
            <div id = 31>31</div>
          </div>
        </div>
        <script type = "text/javascript">
          const WEEKDAYS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
          const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
          let cd = new Date();
          let year = cd.getFullYear();
          let day = cd.getDate();
          let month = cd.getMonth();
          document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          document.getElementsByClassName("previous")[0].addEventListener('click', function(){
            month = month - 1;
            if(month == -1){
              month = 11;
              year--;
              document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
            }
            document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          });
          document.getElementsByClassName("next")[0].addEventListener('click', function(){
            month = month + 1;
            if(month == 12){
              month = 0;
              year++;
              document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
            }
            document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          });
          //todo: add changing dates to correct position dynamically
          // add clickable dates with appoitment times
        </script>
    </body>
</html>
