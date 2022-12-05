<!-- Author: Haider Tawfik-->
<?php
$connection = mysqli_connect("localhost", "root", "", "fitnessTracker");
if ($connection->connect_error)
  {
    die();
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  // for test
  $username = TomTrainer2022;
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['Professional'])){
      $username = $_POST['Professional'];
    }
    if(isset($_POST['addAppoint'])){
      if(isset($_GET['Cdate'])){
        $avail = $_POST['addAppoint']. ":00";
        $q = "INSERT INTO `Professional’s_times_available` (Username, Date, Time) VALUES ('".$username."', '".$_GET['Cdate']."','".$avail."')";
        $a = mysqli_query($connection, $q);
      }
    }
  }
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
              <li> Sun </li>
              <li> Mon </li>
              <li> Tue </li>
              <li> Wed </li>
              <li> Thu </li>
              <li> Fri </li>
              <li> Sat </li>
            </ul>
          </div>
          <div class = "day">
            <br>
          </div>
        </div>
        <div class = "appoints">
          <div class = "header"><h3>Appointments</h3></div>
          <div id = "bookings"></div>
        </div>
        <div id = "addAppointment"> 
          add time available on selected day:
          <form method = "post">
                <input type = "time" name = "addAppoint" id = "addRecAppoint" style = "width: 7vw; height: 1.2rem;">
                <input id = "addExerciseButton" type = "submit" value = "Add Time available">
            </form>
        </div>
        <script type = "text/javascript">
          
          document.getElementById("bookings").innerHTML = <?php 
              echo json_encode($return);?>;
          const dayClass = document.getElementsByClassName("day")[0];
          const WEEKDAYS = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
          const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
          function write(dayInMon, start){
            let toWrite = "";
            for (let i = 0; i < start; i++){
              toWrite += `<div class = 'donthover'></div>`
            }
            for (let i = 1; i <= dayInMon; i++){
              toWrite += `<div id = ${i}> ${i}  </div>`
              
            }
            dayClass.innerHTML = toWrite;
            for(let i = 1; i <= dayInMon; i++){
              let curr = document.getElementById(i);
              curr.addEventListener('click', function onClick(e){
                curr.style.backgroundColor = "aliceblue";
                let day = i;
                document.cookie = 'sel='+day;
                document.cookie = 'mon='+month;
                document.cookie = 'year='+year;
                let cdate = year +"-"+ (month + 1) + "-"+ day;
                window.location.href = "?Cdate="+cdate;
            });
          }
          }
          let client = <?php echo json_encode($clients_arr[$_COOKIE['index']]);?>;
          document.cookie = "p="+client;
          var cd = new Date();
          let year = cd.getFullYear();
          let day = cd.getDate();
          let month = cd.getMonth();
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
          let skipDate = new Date(year + "-" + MONTHS[month] +"-01").getDay();
          write(dayInM, skipDate);
          let selected = <?php echo json_encode($_COOKIE['sel']); ?>;
          if(selected > 0){
            document.getElementById(selected).style.backgroundColor = "aliceblue";
          }
          function checkIfCurrent(year, month){
            cd = new Date();
            if(month == cd.getMonth() && year == cd.getFullYear()){
              document.getElementById(cd.getDate()).style.backgroundColor = "rgb(0, 227, 251)";
            }
          }
          function clearPrev(){
            if(selected > 0){
              document.getElementById(selected).style.backgroundColor = "white";
            }
          }
          checkIfCurrent(year, month);
          document.getElementsByClassName("previous")[0].addEventListener('click', function(){
            month--;
            if(month == -1){
              month = 11;
              year--;
            }
            document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
            dayInM = new Date(year, month + 1, 0).getDate();
            skipDate = new Date(year + "-" + MONTHS[month] +"-01").getDay();
            write(dayInM, skipDate);
            checkIfCurrent(year, month);
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
            skipDate = new Date(year + "-" + (month + 1) +"-01").getDay();
            write(dayInM, skipDate);
            checkIfCurrent(year, month);
            clearPrev();
          });

          //todo: add changing dates to correct position dynamically if time permits if not remove weekdays
        </script>
    </body>
</html>
