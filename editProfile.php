<?php
    $secret = '';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", $secret, "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $result = mysqli_query($connection, "SELECT * FROM Body_Measurement where Username =" .$username);
    $row = mysqli_fetch_array($result);

    $username = $row['Username'];
    $date = $row['Date'];
    $weight = $row["Weight"];
    $waist = $row["Waist"];
    $chest = $row["Chest"];
    $hips = $row["Hips"];

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
                <button id = "b1"><a href = "profile-client.php">Go Back</button>
            </div>
        </header>
        <div>
            <div id = "title">Client's Information</div>
            <form method = "post">
                Username: <input type = "text" name = "Username" value = '<?php echo $row['username'];?>'><br>
                Password: <input type = "text" name = "Password" value = '<?php echo $row['password'];?>'><br>
                Measured Date: <input type = "text" name = "date" value = '<?php echo $row['date'];?>'><br>
                Weight: <input type = "text" name = "weight" value = '<?php echo $row['weight'];?>'><br>
                Chest: <input type = "text" name = "chest" value = '<?php echo $row['chest'];?>'><br>
                Hips: <input type = "text" name = "hips" value = '<?php echo $row['hips'];?>'><br>
                <input type = "submit" value = "Edit">
            </form>
        </div>
        <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
        </footer>
        <script language = "javascript">
            
        </script>
    </body>
</html>