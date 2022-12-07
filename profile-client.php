<?php
    session_start();

    $secret = '';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the log-in page
    if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
    }

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
        if (isset($_POST['username'])){
            $username = $_POST['username'];
            $_SESSION['username'] = $username;
        }

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
            if (mysqli_fetch_array($row)){
                $sql = "INSERT INTO Eats (FoodID, Client_Username, Date) VALUES ('".$newFood."','".$username."','".$today."')";
                mysqli_query($connection, $sql);
            } else{
                exit('<script>alert("It\'s a food that doesn\'t exist on the food list. Please contact the administrator.");location.replace("profile-client.php")</script>');
            }
        }

        if ($newFriend = $_POST['addFr']){
            $row = mysqli_query($connection, "SELECT * FROM Client where Username = '".$newFriend."'");
            if (mysqli_fetch_array($row)){
                $sql = "INSERT INTO Friends_With (Friends_Username, Client_Username) VALUES ('".$newFriend."','".$username."')";
                mysqli_query($connection, $sql);
            } else{
                exit('<script>alert("This user does not exist on our website. Please check the username again.");location.replace("profile-client.php")</script>');
            }
        }
    }

    if (isset($_GET['deletedEx'])){
        $deleted = $_GET['deletedEx'];
        $row = mysqli_query($connection, "SELECT * FROM Performs where Client_Username = '".$username."'");
        $i = 0;
        while ($performed = mysqli_fetch_array($row)){
            if ($i == $deleted){
                $deleted = $performed['Exercise_Name'];
                $sql = "DELETE FROM Performs WHERE Exercise_Name = '".$deleted."'";
                mysqli_query($connection, $sql);
            }
            ++$i;
        }
    }

    if (isset($_GET['deletedFd'])){
        $deleted = $_GET['deletedFd'];
        $row = mysqli_query($connection, "SELECT * FROM Eats where Client_Username = '".$username."'");
        $i = 0;
        while ($ate = mysqli_fetch_array($row)){
            if ($i == $deleted){
                $deleted = $ate['FoodID'];
                $sql = "DELETE FROM Eats WHERE FoodID = '".$deleted."'";
                mysqli_query($connection, $sql);
            }
            ++$i;
        }
    }

    if (isset($_GET['deletedFr'])){
        $deleted = $_GET['deletedFr'];
        $row = mysqli_query($connection, "SELECT * FROM Friends_With where Client_Username = '".$username."'");
        $i = 0;
        while ($friends = mysqli_fetch_array($row)){
            if ($i == $deleted){
                $deleted = $friends['Friends_Username'];
                $sql = "DELETE FROM Friends_With WHERE Friends_Username = '".$deleted."'";
                mysqli_query($connection, $sql);
            }
            ++$i;
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
                Your next Scheduled Appointment is on <?php echo $appointmentDate; ?> at
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
                <li id = "date">Measured on : <?php echo $date; ?></li>
                <li id = "weight">Weight : <?php echo $weight; ?></li>
                <li id = "waist">Waist : <?php echo $waist; ?></li>
                <li id = "chest">Chest : <?php echo $chest; ?></li>
                <li id = "hips">Hips : <?php echo $hips; ?></li>
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
                                        <ol>
                                            <?php
                                                for ($i = 0; $i < count($performedExercise); ++$i){
                                                    echo "<li class = 'exerciseList'>$performedExercise[$i]</li>";
                                                }
                                            ?>
                                        </ol>
                                        <form action = "profile-client.php" method = "post">
                                            <input type = "text" name = "addEx" id = "addedEx" style = "width: 150px; height: 30px;">
                                            <input id = "addExercise" type = "submit" value = "Add Exercise">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td align = center>
                        <div id = "caloriesBurned">
                            <div id = burnedChart></div>
                            <div id = burnedTitle>Calories Burned Today</div>
                        </div>
                    </td>
                    <td align = center>
                        <br><br>
                        <div id = 'allEx'>
                            <H3 id = "workout">• Workout Routine •</H3>
                            <ol>
                                <?php
                                    for ($i = 0; $i < count($allExercise); ++$i){
                                        echo "<li>$allExercise[$i]</li>";
                                    }
                                ?>
                            </ol>
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
                                        <ol>
                                            <?php
                                                for ($i = 0; $i < count($consumedFood); ++$i){
                                                    echo "<li class = 'foodList'>$consumedFood[$i]</li>";
                                                }
                                            ?>
                                        </ol>
                                        <form action = "profile-client.php" method = "post">
                                            <input type = "text" name = "addFd" id = "addedFood" style = "width: 150px; height: 30px;">
                                            <input id = "addFood" type = "submit" value = "Add Food">
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
                    <td align = center>
                        <br><br>
                        <div id = 'allFd'>
                            <H3 id = "mealPlan">• Meal Plan •</H3>
                            <ol>
                                <?php
                                    for ($i = 0; $i < count($allFood); ++$i){
                                        echo "<li>$allFood[$i]</li>";
                                    }
                                ?>
                            </ol>
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
                        <ul>
                        <?php
                            for ($i = 0; $i < count($friends); ++$i){
                                echo "<li class = 'friendList'>$friends[$i]</li>";
                            }
                        ?>
                        </ul>
                        <form action = "profile-client.php" method = "post">
                            <input type = "text" name = "addFr" id = "addedFriends" style = "width: 150px; height: 30px;">
                            <input id = "addFriends" type = "submit" value = "Add Friend">
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
                                    echo "<details><summary>$profs[$i]</summary>$feedback[$i]
                                        <form action = 'request.php' method = 'post'>
                                            <input type = 'hidden' name = 'c_username' value = $username>
                                            <input type = 'hidden' name = 'p_username' value = $profs[$i]>
                                            <input id = 'feedback' type = 'submit' value = 'Request'>
                                        </form>
                                        </details>";
                                }
                            ?>
                        </ol>
                    </td>
                </tr>
            </table>
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
                    else week[i].innerHTML += `<li>${month}.${threeDaysAgo + j}.${year}</li>`;
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

            const exerciseList = document.getElementsByClassName("exerciseList");
            addDelete(exerciseList, "ex");
            const foodList = document.getElementsByClassName("foodList");
            addDelete(foodList, "fd");
            const friendList = document.getElementsByClassName("friendList");
            addDelete(friendList, "fr");

            function addDelete(list, where){
                for (let i = 0; i < list.length; ++i){
                    let del = document.createElement('button');
                    list[i].appendChild(del);
                    del.innerText = "X";
                    del.style.float = "right";
                    del.style.color = "white";
                    del.style.fontSize = "10px";
                    del.style.fontWeight = "bold";
                    del.style.border = "none";
                    del.style.borderRadius = "5px";
                    del.style.backgroundColor = "rgb(41, 52, 130)";
                    del.style.padding = "5px";
                    del.addEventListener("click", function deleteList(e){
                        let deleted = e.target.parentElement;
                        if (where === "ex") location.replace(`profile-client.php?deletedEx=${i}`);
                        else if (where === "fd") location.replace(`profile-client.php?deletedFd=${i}`);
                        else if (where === "fr") location.replace(`profile-client.php?deletedFr=${i}`);
                    });
                }
            }
        </script>
    </body>
</html>