<?php
    $secret = 'this is my password :)...';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $username = $_GET['Username'];

    $measurement = mysqli_query($connection, "SELECT * FROM Body_Measurement where Username =" .$username);
    $date = $_GET[$measurement['Date']];
    $weight = $_GET[$measurement["Weight"]];
    $waist = $_GET[$measurement["Waist"]];
    $chest = $_GET[$measurement["Chest"]];
    $hips = $_GET[$measurement["Hips"]];

    $schedule = mysqli_query($connection, "SELECT * FROM Appointments where Client_Username =" .$username);
    $date = $schedule['Date'];
    $time = $schedule['Time'];

    $routine = mysqli_query($connection, "SELECT * FROM Workout_Routine where Client_Username =" .$username);
    $i = 0;
    while ($row = mysqli_fetch_array($routine)){
        $exercise[$i] = $_GET[$routine['Exercise_Name']];
        ++$i;
    }

    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang = "en-US">
    <head>
        <link href = "./style.css?ver=1" rel = "stylesheet"> <!-- We can put an externally made css file in here -->
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
            <div style = "text-align: center">
                <button id = "b1"><a href = "editProfile.php">Edit Profile</button>
                <button id = "b2"><a href = "logOut.php">Log Out</a></button>
            </div>
            <div id = headerImage>
                <!-- I'm thinking about putting it in for design,
                so should I make it possible for users to upload it themselves? Or any image?
                <img src = "./.png" alt = " " title = " " width = " "> -->
            </div>
        </header>
        <div id = "upcoming">
            <p>
                Your next Scheduled Appointment is on <?php $date ?> at <?php $time ?>
            </p>
        </div>
        <div id = "measurement">
            <ul>
                <li id = "date">Measured on : <?php $date ?></li>
                <li id = "weight">Weight : <?php $weight ?></li>
                <li id = "waist">Waist : <?php $waist ?></li>
                <li id = "chest">Chest : <?php $chest ?></li>
                <li id = "hips">Hips : <?php $hips ?></li>
            </ul>
        </div>
        <div id = "completedExercise">
            <H3>Exercise Completed</H3>
            <table>
                <tr align = center height = "15"></tr>
                    <th class = "day"></th>
                    <th id = "7Days"></th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol type = "I">
                            <!-- ordered list of exercises -->
                            <?php
                                for ($i = 0; $i < count($exercise); ++$i){
                                    "<li>$exercise[$i]</li>";
                                }
                            ?>
                        </ol>
                    </td>
                    <td>
                        <details>
                            <summary>Today</summary>
                                <ol class = "sum"></ol>
                        </details>
                        <!-- the last three days, the next three days, as of today -->
                    </td>
                </tr>
            </table>
        </div>
        <div id = caloriesBurned>
            <!-- donut chart that requires javascript or php -->
            Calories Burned Today
        </div>
        <button id = "addExercise">
            Add Exercise
            <!-- mouse click event that requires javascript or php -->
        </button>
        <div id = "consumedFood">
            <H3>Food's Consumed</H3>
            <table>
                <tr align = center height = "15"></tr>
                    <th class = "day"></th> <!-- today's date -->
                    <th id = "7Days"></th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol>
                            <!-- ordered list of food -->
                        </ol>
                    </td>
                    <td>
                        <details>
                            <summary>Today</summary>
                                <ol class = "sum"></ol>
                            <!-- the last three days, the next three days, as of today -->
                        </details>
                    </td>
                </tr>
            </table>
        </div>
        <div id = caloriesConsumed>
            <!-- donut chart that requires javascript or php -->
            Calories Consumed Today
        </div>
        <button id = "addFood">
            Add Food
            <!-- mouse click event that requires javascript or php -->
        </button>
        <div id = "professionals">
            <table>
                <tr align = center height = "15"></tr>
                    <th>Professionals</th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol> <!-- ordered list of professionals -->
                            <details>
                                <summary><!-- name of professionals --></summary>
                                <!-- Additional info of the professional -->
                            </details>
                            <details>
                                <summary><!-- name of professionals --></summary>
                                <!-- Additional info of the professional -->
                            </details>
                            <details>
                                <summary><!-- name of professionals --></summary>
                                <!-- Additional info of the professional -->
                            </details>
                        </ol>
                    </td>
                </tr>
            </table>
        </div>
        <div id = "Friends">
            <table>
                <tr align = center height = "15"></tr>
                    <th>Professionals</th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol> <!-- ordered list of friend -->
                            <details>
                                <summary><!-- name of friend --></summary>
                                <!-- Additional info of the friend -->
                            </details>
                            <details>
                                <summary><!-- name of friend --></summary>
                                <!-- Additional info of the friend -->
                            </details>
                            <details>
                                <summary><!-- name of friend --></summary>
                                <!-- Additional info of the friend -->
                            </details>
                        </ol>
                        <button id = "addFriends">
                            Add Friends
                            <!-- mouse click event that requires javascript or php -->
                        </button>
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
            console.log(today);
            for (let i = 0; i < today.length; ++i){
                today[i].innerHTML = month + "." + date + "." + year + " (" + day + ")";
            }

            let week = document.getElementsByClassName("sum");
            let threeDaysAgo = date - 3;
            for (let i = 0; i < week.length; ++i){
                for (let j = 0; j < 7; ++j){
                    week[i].innerHTML += `<li>${month}.${threeDaysAgo + j}.${year}</li>`;
                }
            }
        </script>
    </body>
</html>