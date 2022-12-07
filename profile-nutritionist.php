<?php
    session_start();

    $secret = '';

    $connection = mysqli_connect("localhost", "root", $secret, "database");
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the log-in page
    if (isset($_SESSION['username'])){
      $username = $_SESSION['username'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if (isset($_POST['username'])){
        $username = $_POST['username'];
        $_SESSION['username'] = $username;
      }

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
      if(isset($_POST['addClient'])){
        $addClient = $_POST['addClient'];
        $q = "INSERT INTO `Hires` (Client_Username, Professional_Username) VALUES ('".$addClient."', '".$username."')";
        $a = mysqli_query($connection, $q);
      }
    }

    function getTimeEnd($t) {
        if($t < 12) return $t . " A.M";
        else if($t == 12) return $t . " P.M";
        else {
          $t -= 12;
          return $t . " P.M";
        }
    }

    // get all clients of professional
    $q = "SELECT Client_Username FROM Hires where Professional_Username = '".$username."'";
    $clients = mysqli_query($connection, $q);
    for ($i = 0; $i < $clients -> num_rows; $i++){
        $row = mysqli_fetch_array($clients);
        $user = $row['Client_Username'];
        $clients_arr[$i] = $user;
        $q = "SELECT * FROM Body_Measurement where Username = '".$user."'";
        $b_measure = mysqli_query($connection, $q);
        $row = mysqli_fetch_array($b_measure);
        $clients_meas[$i] = "Date: ".$row['Date']."<br/><br/>Weight: ".$row['Weight']."lb<br/><br/>Hips: ".$row['Hips']."cm<br/><br/>Waist: ".$row['Waist']."cm<br/><br/>Chest: ".$row['Chest']."cm";
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
    } else $return = "Select a date to view appointments";

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
      <header>
        <div id = "username">
          <?php
            echo $username
          ?>
        </div> 
        <div id = "option" style = "float: right;">
          <form action = "editProfile.php" method = "post">
              <input type = "hidden" name = "username" value = <?php echo $username;?>>
              <input id = "b1" type = "submit" value = "Edit Profile">
          </form>
          <form action = "logOut.php" method = "post">
              <input id = "b2" type = "submit" value = "Log Out">
          </form>
        </div>
      </header>
      <div id = "upcoming">
        <p>
          <?php echo "Your next appointment is on " .$date. " with " .$client. " at ". $time;?>
        </p>
      </div>
      <div id = "clients">
        <table>
          <tr align = "center">
            <td>Clients</td>
          </tr>
          <tr align = "center">
            <td>
                <ol>
                <?php
                  for ($i = 0; $i < count($clients_arr); $i++){
                    echo "<details class = 'det'><summary>$clients_arr[$i]</summary>$clients_meas[$i]
                            <form action = 'feedbackN.php' method = 'post'>
                              <input type = 'hidden' name = 'c_username' value = $clients_arr[$i]>
                              <input type = 'hidden' name = 'p_username' value = $username>
                              <input id = 'feedback' type = 'submit' value = 'Feedback'>
                            </form>
                          </details>";
                  }
                ?>
                </ol>
                <form action = "profile-nutritionist.php" method = "post">
                    <input type = "text" name = "addClient" id = "addedClient" style = "width: 150px; height: 30px;">
                    <input id = "addClient" type = "submit" value = "Add Client">
                </form>
            </td>
          </tr>
        </table>
      </div>
      <div class = "cal">
        <div class = "month">
          <ul>
            <li class = "previous"> < </li>
            <li id = cm></li>
            <li class = "next"> > </li>
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
        <div id = "day">
          <br>
        </div>
      </div>
      <div class = "appoints">
          <div class = "header"><h3>• Appointments</h3></div>
          <div id = "bookings"></div>
        </div>
      <div id = "addAppointment">
        Add time available on selected day:
        <form method = "post">
            <input type = "time" name = "addAppoint" id = "addRecAppoint" style = "width: 100px; height: 35px;">
            <input id = "addExerciseButton" type = "submit" value = "Add Time available">
        </form>
      </div>
      <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
      </footer>
      <script type = "text/javascript">
          document.getElementById("bookings").innerHTML = <?php echo json_encode($return);?>;
          const dayClass = document.getElementById("day");
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
              document.getElementById(cd.getDate()).style.color = "white";
              document.getElementById(cd.getDate()).style.backgroundColor = "rgb(101, 175, 248)";
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
      </script>
    </body>
</html>