<?php
    $secret = 'this is my password :)...';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $result = mysqli_query($connection, "SELECT * FROM Body_Measurement where Username =" .$username);
    $username = $_GET[$result['Username']];
    $date = $_GET[$result['Date']];
    $weight = $_GET[$result["Weight"]];
    $waist = $_GET[$result["Waist"]];
    $chest = $_GET[$result["Chest"]];
    $hips = $_GET[$result["Hips"]];

    $schedule = mysqli_query($connection, "SELECT * FROM Appointments where Client_Username =" .$username);
    $date = $schedule['Date'];
    $time = $schedule['Time'];

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
                <li id = "date"><?php $date ?></li>
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
                    <th></th> <!-- today's date -->
                    <th></th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol>
                            <!-- ordered list of exercises -->
                        </ol>
                    </td>
                    <td>
                        <summary>Today</summary>
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
                    <th></th> <!-- today's date -->
                    <th></th>
                </tr>
                <tr align = "center">
                    <td>
                        <ol>
                            <!-- ordered list of exercises -->
                        </ol>
                    </td>
                    <td>
                        <details>
                            <summary>Today</summary>
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
        <script language = "javascript">
            
        </script>
    </body>
</html>