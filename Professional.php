<?php
$connection = mysqli_connect("localhost", "root", "", "fitnessTracker");
if ($connection->connect_error)
  {
    die();
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  // for test
  $username = TomTrainer2022;
  function getTimeEnd($t) {
    if($t < 12) {
      return $t . " A.M";
    }else if($t == 12){
      return $t . " P.M";
    } else {
      $t -= 12;
      return $t . " P.M";
    }
  }
  // get next appointment

  // get all clients of professional
  $q = "SELECT Client_Username FROM Hires where Professional_Username = '".$username."'";
  $clients = mysqli_query($connection, $q);
  for($i = 0; $i < $clients -> num_rows; $i++){
    $row = mysqli_fetch_array($clients);
    $user = $row['Client_Username'];
    $clients_arr[$i] = $user;
    $q = "SELECT * FROM Body_Measurement where Username = '".$user."'";
    $b_measure = mysqli_query($connection, $q);
    $row = mysqli_fetch_array($b_measure);
    $clients_meas[$i] = "Date: ".  $row['Date'] . "<br /> Weight: ". $row ['Weight'] . "lb <br />Hips: ". $row['Hips'] . "cm <br />Hips: ". $row['Waist'] ."cm <br />Chest: ". $row['Chest'] . "cm";
  }
  echo $client_meas[0];
  echo "Returned rows are: " . $clients -> num_rows;
  $test = "2022-11-22";
  $q = "SELECT * FROM Appointments WHERE Professional_username = '".$username."' AND Date = '".$test."'";
  $a = mysqli_query($connection, $q);
  echo "rows" . $a -> num_rows;
    // Free result set
  //$test -> free_result();
  if(isset($_GET['Cdate'])){
    $cdate = $_GET['Cdate'];
    $q = "SELECT * FROM Appointments WHERE Professional_username = '".$username."' AND Date = '".$cdate."' Order By Date, Time";
    $a = mysqli_query($connection, $q);
    if($a->num_rows == 0){
      $return = "No Appointments";
    } else {
      while($row = mysqli_fetch_array($a)){
        $return = $return . "Appointment with: ". $row['Client_Username']. " At " . getTimeEnd($row['Time']). "\n";
      }
    }
  } else {
    $return = "Select a date to view appointments";
  }
  $c_date = date("Y/m/d");
  $q = "SELECT * FROM Appointments WHERE Professional_Username = '".$username."' AND Date >= '".$c_date."' ORDER BY Date ASC, Time";
  $appoints = $connection -> query($q);
  $row = mysqli_fetch_array($appoints);
  $date = $row['Date'];
  $client = $row['Client_Username'];
  $time = getTimeEnd($row['Time']);
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
          <button id = "logOut"> <a href = "logOut.php"> LogOut </a> </button>
        </div>
        <div>
          <?php
          echo "Your next appointment is on " .$date. " with " .$client. " at ". $time;
          ?>
        </div>
        <div id = "clients">
          <table style = "float: right;" WIDTH = 200>
            <tr>
              <th>Clients</th>
            </tr>
            <tr>
                  <?php
                    for($i = 0; $i < count($clients_arr); $i++){
                      echo "<tr>";
                      echo 
                      "<td>
                        <details class = 'det'><summary>$clients_arr[$i]</summary>$clients_meas[$i]. 
                        <form action = 'feedback.php' method = 'post'>
                          
                          <input type = 'hidden' name = 'c_username' value = $clients_arr[$i]>
                          <input type = 'hidden' name = 'p_username' value = $username>
                          <input class = 'feedback' type = 'submit' value = 'feedback'>
                        </form>
                      </details>
                      </td>";
                      echo "</tr>";
                    }
                  ?>
            </td>
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
            <br>
            <div id = 1></div>
            <div id = 2></div>
            <div id = 3></div>
            <div id = 4></div>
            <div id = 5></div>
            <div id = 6></div>
            <div id = 7></div>
            <div id = 8></div>
            <div id = 9></div>
            <div id = 10></div>
            <div id = 11></div>
            <div id = 12></div>
            <div id = 13></div>
            <div id = 14></div>
            <div id = 15></div>
            <div id = 16></div>
            <div id = 17></div>
            <div id = 18></div>
            <div id = 19></div>
            <div id = 20></div>
            <div id = 21></div>
            <div id = 22></div>
            <div id = 23></div>
            <div id = 24></div>
            <div id = 25></div>
            <div id = 26></div>
            <div id = 27></div>
            <div id = 28></div>
            <div id = 29></div>
            <div id = 30></div>
            <div id = 31></div>
          </div>
        </div>
        <div class = "appoints">
          <div class = "header"><h3>Appointments</h3></div>
          <div id = "bookings"></div>
        </div>
        <script type = "text/javascript">
          
          document.getElementById("bookings").innerHTML = <?php 
              echo json_encode($return);?>;
          let selected = <?php echo json_encode($_COOKIE['sel']); ?>;
          if(selected > 0){
            document.getElementById(selected).style.backgroundColor = "aliceblue";
          }
          function clearPrev(){
            if(selected > 0){
              document.getElementById(selected).style.backgroundColor = "white";
            }
          }
          function test1(i){
            document.cookie = "index="+i;
            let client = <?php echo json_encode($clients_arr[$_COOKIE['index']]);?>;
            let user = <?php echo json_encode($username)?>;
            document.cookie = "user="+user;
            document.cookie = "p="+client;
          }
          let client = <?php echo json_encode($clients_arr[$_COOKIE['index']]);?>;
          document.cookie = "p="+client;
          const WEEKDAYS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
          const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
          let cd = new Date();
          let year = cd.getFullYear();
          let day = cd.getDate();
          let month = cd.getMonth();
          let previous = document.getElementById(1);
          let pMonth = <?php if (isset($_COOKIE['mon'])){
            echo $_COOKIE['mon']; 
          } else {echo -1;} ?>;
          if(pMonth >= 0){
            month = pMonth;
          }
          let pYear = <?php if (isset($_COOKIE['year'])){
            echo $_COOKIE['year'];
            } else {
              echo -1;
            } ?>;
          if(pYear >= 0){
            year = pYear;
          }
          document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          let dayInM = new Date(year, month + 1, 0).getDate();
          for (let i = 1; i <= dayInM; i++){
              document.getElementById(i).innerHTML = i;
            }
          document.getElementsByClassName("previous")[0].addEventListener('click', function(){
            month--;
            if(month == -1){
              month = 11;
              year--;
            }
            document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
            dayInM = new Date(year, month + 1, 0).getDate();
            for (let i = 1; i <= 31; i++){
              document.getElementById(i).innerHTML = i;
              if(i > dayInM) document.getElementById(i).innerHTML = "";
            }
            clearPrev();
          });
          document.getElementsByClassName("next")[0].addEventListener('click', function(){
            month++;
            if(month == 12){
              month = 0;
              year++;
            }
            document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
            dayInM = new Date(year, month + 1, 0).getDate();
            for (let i = 1; i <= 31; i++){
              document.getElementById(i).innerHTML = i;
              if(i > dayInM) document.getElementById(i).innerHTML = "";
            }
            clearPrev();
          });
          for(let i = 1; i <= 31; i++){
            let curr = document.getElementById(i);
            curr.addEventListener('click', function onClick(e){
              curr.style.backgroundColor = "aliceblue";
              previous.style.backgroundColor = "white";
              let day = i;
              document.cookie = 'sel='+day;
              document.cookie = 'mon='+month;
              document.cookie = 'year='+year;
              previous = curr;
              let cdate = year +"-"+ (month + 1) + "-"+ day;
              window.location.href = "?Cdate="+cdate;
          });
          }

          //todo: add changing dates to correct position dynamically if time permits if not remove weekdays
        </script>
    </body>
</html>
