<?php
    $secret = '';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    // Get the username from the profile page
    $username = $_POST['username'];

    $row = mysqli_query($connection, "SELECT * FROM User WHERE Username = '".$username."'");
    if ($update = mysqli_fetch_array($row)) $password = $update['Password'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if ($newName = $_POST['newName']) {
            $sql = "UPDATE User SET Username = '".$newName."' AND Password = '".$password."' WHERE Username = '".$username."'";
            $result = mysqli_query($connection, $sql);
            if ($result == false) echo "noooooooooo";
            else echo "succeed";
            $username = $newName;
        }
        // $password = $_POST['password'];
        // $date = $_POST['date'];
        // $weight = $_POST["weight"];
        // $waist = $_POST["waist"];
        // $chest = $_POST["chest"];
        // $hips = $_POST["hips"];
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
            <div id = "option" style = "float: right;">
                <form action = "profile-client.php" method = "post">
                    <input type = "hidden" name = "username" value = <?php echo $newName;?>>
                    <input id = "b1" type = "submit" value = "Go Back">
                </form>
            </div>
        </header>
        <div id = "title">User's Information</div>
        <div id = "userInfo">
            <form action = "editProfile.php" method = "post">
                <label>
                    <span>Username:</span><input type = "text" name = "newName" style = "width: 150px; height: 30px;">
                </label>
                <input type = "hidden" name = "username" value = <?php echo $username;?>>
                <label>
                    <span>Password:</span><input type = "text" name = "password" style = "width: 150px; height: 30px;">
                </label>
                <label>
                    <span>Measured Date:</span><input type = "text" name = "date" style = "width: 150px; height: 30px;">
                </label>
                <label>
                    <span>Weight:</span><input type = "text" name = "weight" style = "width: 150px; height: 30px;">
                </label>
                <label>
                    <span>Waist:</span><input type = "text" name = "waist" style = "width: 150px; height: 30px;">
                </label>
                <label>
                    <span>Chest:</span><input type = "text" name = "chest" style = "width: 150px; height: 30px;">
                </label>
                <label>
                    <span>Hips:</span><input type = "text" name = "hips" style = "width: 150px; height: 30px;">
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