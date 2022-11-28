<?php
    $secret = '';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the log-in page
    // I hard-coded it for now, I need to check if it is linked to the login page
    $username = $_POST['Username'];
    $username = "JennySmith123";

    // Get the body measurements
    $row = mysqli_query($connection, "SELECT * FROM Body_Measurement where Username = '".$username."'");
    $measurement = mysqli_fetch_array($row);
    $date = $measurement['Date'];
    $weight = $measurement["Weight"];
    $waist = $measurement["Waist"];
    $chest = $measurement["Chest"];
    $hips = $measurement["Hips"];

    // Get the next schedule
    date_default_timezone_set('Canada/Mountain');
    $today = date('Y-m-d', time());
    $row = mysqli_query($connection, "SELECT * FROM Appointments where Client_Username = '".$username."'");
    while ($schedule = mysqli_fetch_array($row)){
        if (strtotime($schedule['Date']) > strtotime($today)){
            $appointmentDate = $schedule['Date'];
            $appointmentTime = $schedule['Time'];
            break;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if ($newExercise = $_POST['addEx']){
            $row = mysqli_query($connection, "SELECT * FROM Exercise where Exercise_Name = '".$newExercise."'");
            if (mysqli_fetch_array($row)){ 
                $sql = "INSERT INTO Performs (Exercise_Name, Client_Username, Date) VALUES ('".$newExercise."','".$username."','".$today."')";
                mysqli_query($connection, $sql);
            } else{
                exit('<script>alert("It\'s a workout that doesn\'t exist on the exercise list. Please contact the administrator.");location.replace("profile-client.php")</script>');
            }
        }

        if ($newFood = $_POST['addFd']){
            $row = mysqli_query($connection, "SELECT * FROM Food_Item where FoodID = '".$newFood."'");
            if ( mysqli_fetch_array($row)){
                $sql = "INSERT INTO Eats (FoodID, Client_Username, Date) VALUES ('".$newFood."','".$username."','".$today."')";
                mysqli_query($connection, $sql);
            } else{
                exit('<script>alert("It\'s a food that doesn\'t exist on the food list. Please contact the administrator.");location.replace("profile-client.php")</script>');
            }
        }

        if ($newFriend = $_POST['addFr']){
            $row = mysqli_query($connection, "SELECT * FROM User where Username = '".$newFriend."'");
            if (mysqli_fetch_array($row)){
                $sql = "INSERT INTO Friends_With (Friends_Username, Client_Username) VALUES ('".$newFriend."','".$username."')";
                mysqli_query($connection, $sql);
            } else{
                exit('<script>alert("This user does not exist on our website. Please check the username again.");location.replace("profile-client.php")</script>');
            }
        }
    }

    // Get the performed exercises
    $row = mysqli_query($connection, "SELECT * FROM Performs where Client_Username = '".$username."'");
    $i = 0;
    while ($performed = mysqli_fetch_array($row)){
        if (strtotime($performed['Date']) == strtotime($today)){
            $performedExercise[$i] = $performed['Exercise_Name'];
            ++$i;
        }
    }
    $calories_Burned = 0;
    for ($i = 0; $i < count($performedExercise); ++$i){
        $row = mysqli_query($connection, "SELECT * FROM Exercise where Exercise_Name = '".$performedExercise[$i]."'");
        while ($temp = mysqli_fetch_array($row)){
            $calories_Burned += $temp['Calories_Burned'];
        }
    }

    // Get the workout routine
    $row = mysqli_query($connection, "SELECT * FROM Workout_Routine where Client_Username = '".$username."'");
    $i = 0;
    while ($routine = mysqli_fetch_array($row)){
        $allExercise[$i] = $routine['Exercise_Name'];
        ++$i;
    }
    $totalBurnCalories = 0;
    for ($i = 0; $i < count($allExercise); ++$i){
        $row = mysqli_query($connection, "SELECT * FROM Exercise where Exercise_Name = '".$allExercise[$i]."'");
        while ($temp = mysqli_fetch_array($row)){
            $totalBurnCalories += $temp['Calories_Burned'];
        }
    }

    // Get the consumed food
    $row = mysqli_query($connection, "SELECT * FROM Eats where Client_Username = '".$username."'");
    $i = 0;
    while ($ate = mysqli_fetch_array($row)){
        if (strtotime($ate['Date']) == strtotime($today)){
            $consumedFood[$i] = $ate['FoodID'];
            ++$i;
        }
    }
    $calories_Earned = 0;
    for ($i = 0; $i < count($consumedFood); ++$i){
        $row = mysqli_query($connection, "SELECT * FROM Food_Item where FoodID = '".$consumedFood[$i]."'");
        while ($temp = mysqli_fetch_array($row)){
            $calories_Earned += $temp['Calories'];
        }
    }

    // Get the meal plan
    $row = mysqli_query($connection, "SELECT * FROM Recommended_Meal_Plan where Client_Username = '".$username."'");
    $i = 0;
    while ($meal = mysqli_fetch_array($row)){
        $allFood[$i] = $meal['FoodID'];
        ++$i;
    }
    $totalEarnCalories = 0;
    for ($i = 0; $i < count($allFood); ++$i){
        $row = mysqli_query($connection, "SELECT * FROM Food_Item where FoodID = '".$allFood[$i]."'");
        while ($temp = mysqli_fetch_array($row)){
            $totalEarnCalories += $temp['Calories'];
        }
    }

    // Get friends
    $row = mysqli_query($connection, "SELECT * FROM Friends_With where Client_Username = '".$username."'");
    $i = 0;
    while ($friendsWith = mysqli_fetch_array($row)){
        $friends[$i] = $friendsWith['Friends_Username'];
        ++$i;
    }

    // Get professionals
    $row = mysqli_query($connection, "SELECT * FROM Hires where Client_Username = '".$username."'");
    $i = 0;
    while ($hired = mysqli_fetch_array($row)){
        $profs[$i] = $hired['Professional_Username'];
        $feedback[$i] = $hired['Feedback'];
        ++$i;
    }

    mysqli_close($connection);
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
                    echo $username;
                ?>
            </div>
            <div id = "option" style = "float: right;">
                <button id = "b1"><a href = "editProfile.php?mode=update" method = "get">Edit Profile</button>
                <button id = "b2"><a href = "logOut.php">Log Out</a></button>
            </div>
        </header>
        <div id = "upcoming">
            <p>
                Your next Scheduled Appointment is on <?php echo $appointmentDate ?> at
                <?php
                    if ($appointmentTime < 12) echo $appointmentTime." A.M.";
                    else if ($appointmentTime == 12) echo $appointmentTime." P.M.";
                    else {
                        $appointmentTime -= 12;
                        echo $appointmentTime." P.M.";
                    }
                ?>
            </p>
        </div>
        <div id = "measurement">
            <ul>
                <li id = "date">Measured on : <?php echo $date ?></li>
                <li id = "weight">Weight : <?php echo $weight ?></li>
                <li id = "waist">Waist : <?php echo $waist ?></li>
                <li id = "chest">Chest : <?php echo $chest ?></li>
                <li id = "hips">Hips : <?php echo $hips ?></li>
            </ul>
        </div>
        <div id = "section1">
            <table>
                <tr>
                    <td>
                        <div id = "completedExercise">
                            <H3>• Exercise Completed</H3>
                            <table>
                                <tr align = center>
                                    <td class = "day"></td>
                                    <td id = "sevenDays" rowspan = "2">
                                        <details>
                                            <summary>A Week</summary>
                                                <ul class = "sum"></ul>
                                        </details>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <ol id = "exerciseList">
                                            <?php
                                                for ($i = 0; $i < count($performedExercise); ++$i){
                                                    echo "<li>$performedExercise[$i]</li>";
                                                }
                                            ?>
                                        </ol>
                                        <form action = "profile-client.php" method = "post">
                                            <input type = "text" name = "addEx" id = "addedEx" style = "width: 150px; height: 30px;">
                                            <input id = "addExercise" type = "submit" value = "Add Exercise" onclick = "addExercise()">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td align = center rowspan = "2">
                        <div id = "caloriesBurned">
                            <div id = burnedChart></div>
                            <div id = burnedTitle>Calories Burned Today</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <br></br>
        <div id = "section2">
            <table>
                <tr>
                    <td>
                        <div id = "consumedFood">
                            <H3>• Food's Consumed</H3>
                            <table>
                                <tr align = center>
                                    <td class = "day"></td>
                                    <td id = "sevenDays" rowspan = "2">
                                        <details>
                                            <summary>A Week</summary>
                                                <ul class = "sum"></ul>
                                        </details>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <ol id = "foodList">
                                            <?php
                                                for ($i = 0; $i < count($consumedFood); ++$i){
                                                    echo "<li>$consumedFood[$i]</li>";
                                                }
                                            ?>
                                        </ol>
                                        <form action = "profile-client.php" method = "post">
                                            <input type = "text" name = "addFd" id = "addedFood" style = "width: 150px; height: 30px;">
                                            <input id = "addFood" type = "submit" value = "Add Food" onclick = "addFood()">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td align = center rowspan = "2">
                        <div id = "caloriesConsumed">
                            <div id = consumedChart></div>
                            <div id = consumedTitle>Calories Consumed Today</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id = "friends">
            <table>
                <tr align = "center">
                    <td>Friends</td>
                </tr>
                <tr align = "center">
                    <td>
                        <ul id = "friendlist">
                        <?php
                            for ($i = 0; $i < count($friends); ++$i){
                                echo "<li>$friends[$i]</li>";
                            }
                        ?>
                        </ul>
                        <form action = "profile-client.php" method = "post">
                            <input type = "text" name = "addFr" id = "addedFriends" style = "width: 150px; height: 30px;">
                            <input id = "addFriends" type = "submit" value = "Add Friend" onclick = "addFriends()">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div id = "professionals">
            <table>
                <tr align = "center">
                    <td>Professionals</td>
                </tr>
                <tr align = "center">
                    <td>
                        <ol>
                            <?php
                                for ($i = 0; $i < count($profs); ++$i){
                                    echo "<details><summary>$profs[$i]</summary>$feedback[$i]</details>";
                                }
                            ?>
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
        <div id = "previous" class = "popup" style = "display:none;">
            
        </div>
        <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
        </footer>
        <script type="text/javascript">
            let today = document.getElementsByClassName("day");
            
            let now = new Date();
            let year = now.getFullYear();
            let month = now.getMonth() + 1; //.getMonth() = current month - 1
            let date = now.getDate();
            let day = now.getDay();

            if (day === 0) day = "Sunday";
            else if (day === 1) day = "Monday";
            else if (day === 2) day = "Tuesday";
            else if (day === 3) day = "Wednesday";
            else if (day === 4) day = "Thursday";
            else if (day === 5) day = "Friday";
            else if (day === 6) day = "Saturday";
            
            for (let i = 0; i < today.length; ++i){
                today[i].innerHTML = "[" + day + "] " + month + "." + date + "." + year;
            }

            let week = document.getElementsByClassName("sum");
            let threeDaysAgo = date - 3;
            for (let i = 0; i < week.length; ++i){
                for (let j = 0; j < 7; ++j){
                    if (j == 3) week[i].innerHTML += `<li>Today</li>`;
                    else week[i].innerHTML += `<li><a href = "#previous">${month}.${threeDaysAgo + j}.${year}</a></li>`;
                }
            }

            // (Burned Calories / Total calories user has to burn today) * 100
            const burned = document.getElementById("burnedChart");
            let totalCalorie = "<?php echo $totalBurnCalories;?>";
            let todayBurned = "<?php echo $calories_Burned;?>";
            let percentage = (todayBurned / totalCalorie) * 100;
            burned.style.background = `conic-gradient(#009966 ${percentage}%, rgb(220, 225, 230) ${percentage}%)`;
            
            // (Consumed Calories / Total calories user has to consume today) * 100
            const consumed = document.getElementById("consumedChart");
            totalCalorie = "<?php echo $totalEarnCalories;?>";
            let todayConsumed = "<?php echo $calories_Earned;?>";
            percentage = (todayConsumed / totalCalorie) * 100;
            consumed.style.background = `conic-gradient(#009966 ${percentage}%, rgb(220, 225, 230) ${percentage}%)`;

            const previous = document.getElementById("consumedChart");

            function addExercise(){
                const exerciseButton = document.getElementById("addExercise");
                let addition = document.getElementById("addedEx");
                const exerciseList = document.getElementById("exerciseList");
                if (addition.value == "") return;
                let list = document.createElement("li");
                list.innerHTML = addition.value;
                exerciseList.appendChild(list);
            }

            function addFood(){
                const foodButton = document.getElementById("addFood");
                let addition = document.getElementById("addedFood");
                const foodList = document.getElementById("foodList");
                if (addition.value == "") return;
                let list = document.createElement("li");
                list.innerHTML = addition.value;
                foodList.appendChild(list);
            }

            function addFriends(){
                const friendButton = document.getElementById("addFriends");
                let addition = document.getElementById("addedFriends");
                const friendlist = document.getElementById("friendlist");
                if (addition.value == "") return;
                let list = document.createElement("li");
                list.innerHTML = addition.value;
                friendlist.appendChild(list);
            }
        </script>
    </body>
</html>