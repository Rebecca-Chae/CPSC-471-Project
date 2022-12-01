<?php
    $secret = '';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the profile page/refresh
    if (isset($_GET['user'])) $username = $_GET['user'];
    else $username = $_POST['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $date = $_POST['date'];
        $weight = $_POST['weight'];
        $waist = $_POST['waist'];
        $chest = $_POST['chest'];
        $hips = $_POST['hips'];

        if (isset($date) && isset($weight) && isset($waist) && isset($chest) && isset($hips)){
            $sql = "UPDATE Body_Measurement
                    SET Date = '{$date}',
                        Weight = {$weight},
                        Waist = {$waist},
                        Chest = {$chest},
                        Hips = {$hips}
                    WHERE Username = '{$username}'";
            $result = mysqli_query($connection, $sql);
        }
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
                Fitness Tracker
            </div>
            <div id = "option" style = "float: right;">
                <form action = "profile-client.php" method = "post">
                    <input type = "hidden" name = "username" value = <?php echo $newName;?>>
                    <input id = "b1" type = "submit" value = "Go Back">
                </form>
            </div>
        </header>
        <div id = "title"><?php echo $username;?>'s Information</div>
        <div id = "userInfo">
            <form action = "editProfile.php" method = "post">
                <input type = "hidden" name = "username" value = <?php echo $username;?>>
                <label>
                    <span>Measured Date:</span><input type = "date" name = "date" style = "width: 150px; height: 30px;" required>
                </label>
                <label>
                    <span>Weight:</span><input type = "number" step = "0.1" name = "weight" style = "width: 150px; height: 30px;" required>
                </label>
                <label>
                    <span>Waist:</span><input type = "number" step = "0.1" name = "waist" style = "width: 150px; height: 30px;" required>
                </label>
                <label>
                    <span>Chest:</span><input type = "number" step = "0.1" name = "chest" style = "width: 150px; height: 30px;" required>
                </label>
                <label>
                    <span>Hips:</span><input type = "number" step = "0.1" name = "hips" style = "width: 150px; height: 30px;" required>
                </label>
                <input id = "edit" type = "submit" value = "Edit">
            </form>
        </div>
        <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
        </footer>
        <script language = "javascript">
            
        </script>
    </body>
</html>