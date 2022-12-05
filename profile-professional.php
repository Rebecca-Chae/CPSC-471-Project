<?php
    $secret = '';

    $connection = mysqli_connect("localhost", "root", $secret, "database");
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the log-in page
    if (!($username = $_POST['username'])) $username = "TomTrainer2022";

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
                            <form action = 'feedback.php' method = 'post'>
                              <input type = 'hidden' name = 'c_username' value = $clients_arr[$i]>
                              <input type = 'hidden' name = 'p_username' value = $username>
                              <input id = 'feedback' type = 'submit' value = 'Feedback'>
                            </form>
                          </details>";
                  }
                ?>
              <ol>
            </td>
          </tr>
        </table>
      </div>
      <table id = "cal" align = "center">
        <thead>
          <tr>
            <td align = "center" id = "previous" onclick = "previous()"> < </td>
            <td align = "center" id = "month" colspan = "5"></td>
            <td align = "center" id = "next" onclick = "next()"> > </td>
          </tr>
          <tr>
            <td id = "sun" align = "center">Sun</td>
            <td align = "center">Mon</td>
            <td align = "center">Tue</td>
            <td align = "center">Wed</td>
            <td align = "center">Thu</td>
            <td align = "center">Fri</td>
            <td id = "sat" align = "center">Sat</td>
          </tr>
          <tr class = "appoints"><h3>Appointments</h3></tr>
        </thead>
        <tbody id = "calendarDays"></tbody>
      </table>
      <div class = "appoints">
        <h3>Appointments</h3>
        <div id = "bookings"></div>
      </div>
      <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
      </footer>
      <script type = "text/javascript">
          // document.getElementById("bookings").innerHTML = <?php echo json_encode($return);?>;
          // let selected = <?php echo json_encode($_COOKIE['sel']); ?>;
          // if (selected > 0){
          //     document.getElementById(selected).style.backgroundColor = "aliceblue";
          // }

          // function clearPrev(){
          //     if(selected > 0){
          //         document.getElementById(selected).style.backgroundColor = "white";
          //     }
          // }
          
          // function test1(i){
          //     document.cookie = "index="+i;
          //     let client = <?php echo json_encode($clients_arr[$_COOKIE['index']]);?>;
          //     let user = <?php echo json_encode($username)?>;
          //     document.cookie = "user="+user;
          //     document.cookie = "p="+client;
          // }
          
          // let client = <?php echo json_encode($clients_arr[$_COOKIE['index']]);?>;
          // document.cookie = "p="+client;
          // const WEEKDAYS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
          // const MONTHS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
          // let cd = new Date();
          // let year = cd.getFullYear();
          // let day = cd.getDate();
          // let month = cd.getMonth();
          // let previous = document.getElementById(1);
          // let pMonth = <?php
          //                 if (isset($_COOKIE['mon'])) {
          //                     echo $_COOKIE['mon']; 
          //                 } else echo -1;
          //                 ?>;
          // if (pMonth >= 0) month = pMonth;
          
          // let pYear = <?php
          //                 if (isset($_COOKIE['year'])){
          //                     echo $_COOKIE['year'];
          //                 } else echo -1;
          //                 ?>;
          // if (pYear >= 0) year = pYear;
          
          // document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          // let dayInM = new Date(year, month + 1, 0).getDate();
          
          // for (let i = 1; i <= dayInM; i++){
          //     document.getElementById(i).innerHTML = i;
          // }
          
          // document.getElementsByClassName("previous")[0].addEventListener('click', function(){
          // month--;
          
          // if(month == -1){
          //     month = 11;
          //     year--;
          // }
          
          // document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          // dayInM = new Date(year, month + 1, 0).getDate();
          
          // for (let i = 1; i <= 31; i++){
          //     document.getElementById(i).innerHTML = i;
          //     if(i > dayInM) document.getElementById(i).innerHTML = "";
          // }
          
          // clearPrev();
          // });

          // document.getElementsByClassName("next")[0].addEventListener('click', function(){
          //     month++;
          //     if(month == 12){
          //         month = 0;
          //         year++;
          //     }
              
          //     document.getElementById("cm").innerHTML = MONTHS[month] + " " + year;
          //     dayInM = new Date(year, month + 1, 0).getDate();
              
          //     for (let i = 1; i <= 31; i++){
          //         document.getElementById(i).innerHTML = i;
          //         if(i > dayInM) document.getElementById(i).innerHTML = "";
          //     }
              
          //     clearPrev();
          // });

          // for (let i = 1; i <= 31; i++){
          //     let curr = document.getElementById(i);
          //     curr.addEventListener('click', function onClick(e){
          //         curr.style.backgroundColor = "aliceblue";
          //         previous.style.backgroundColor = "white";
          //         let day = i;
          //         document.cookie = 'sel='+day;
          //         document.cookie = 'mon='+month;
          //         document.cookie = 'year='+year;
          //         previous = curr;
          //         let cdate = year +"-"+ (month + 1) + "-"+ day;
          //         window.location.href = "?Cdate="+cdate;
          //     });
          // }
        //}

        // Displaying Calendar Citatoin: https://wooder2050.medium.com/바닐라코딩-자바스크립트로-달력-calendar-todo-구현하기-f635ef8cce76
        let currentMonth = document.getElementById("month");
        let dates = document.getElementById("calendarDays");

        let today = new Date();
        let firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        let days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        let months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        let leap = [31, 29, 31,30, 31, 30, 31, 31, 30, 31, 30, 31];
        let notLeap = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        currentMonth.innerHTML = months[firstDay.getMonth()] + '&nbsp;' + firstDay.getFullYear();

        let currentYear;
        if (today.getFullYear() % 4 === 0) currentYear = leapYear;
        else currentYear = notLeap;

        function calendar(){
          let date = 1;
          // Maximum 6 weeks in a month
          for(let i = 0; i < 6; ++i){
              let week = document.createElement('tr');
              week.setAttribute("id", i);
              // 7 days in a week
              for (let j = 0; j < 7; ++j){
                // Skip until the first day of current month || date should be smaller than current month's total num of dates
                if ((i === 0 && j < firstDay.getDay()) || date > currentYear[firstDay.getMonth()]){
                  let days = document.createElement("td");
                  days.style.textAlign = "center";
                  week.appendChild(days);
                } else{
                  let days = document.createElement("td");
                  days.textContent = date;
                  days.style.textAlign = "center";
                  days.setAttribute("id", date);
                  week.appendChild(days);
                  date++;
                }
              }
              dates.appendChild(week);
          }
        }
        calendar();

        function previous(){
          if (firstDay.getMonth() === 1){
            firstDay = new Date(firstDay.getFullYear() - 1, 12, 1);
            if (firstDay.getFullYear() % 4 === 0) currentYear = leapYear;
            else currentYear = notLeap;
          } else firstDay = new Date(firstDay.getFullYear(), firstDay.getMonth() - 1, 1);
        
          today = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
          currentMonth.innerHTML = months[firstDay.getMonth()] + '&nbsp;' + firstDay.getFullYear();
          
          for (let i = 0; i < 6; ++i){
            document.getElementById(i).remove();
          }
          
          calendar();
        }

        function next(){
          if (firstDay.getMonth() === 12){
            firstDay = new Date(firstDay.getFullYear() + 1, 1, 1);
            if (firstDay.getFullYear() % 4 === 0) currentYear = leapYear;
            else currentYear = notLeap;
          } else firstDay = new Date(firstDay.getFullYear(), firstDay.getMonth() + 1, 1);
        
          today = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
          currentMonth.innerHTML = months[firstDay.getMonth()] + '&nbsp;' + firstDay.getFullYear();
          
          for (let i = 0; i < 6; ++i){
            document.getElementById(i).remove();
          }
          
          calendar();
        }

      </script>
    </body>
</html>