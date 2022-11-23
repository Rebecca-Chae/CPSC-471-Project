<?php
    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "root", " ", "database");

    // . 접합. 변수끼리 + 하는 거랑 같음
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $result = mysqli_query($connection, "SELECT * FROM Body_Measurement where Username =" .$username);

    // Since I already found the same user with the same ID, I don't think I need to put it in the loop?
    // while ($row = mysqli_fetch_array($result)){ 
        ?>
        <form action = "profile-client.php?update=edit" method = "post">
            Username: <input type = "text" name = "Username" value = '<?php echo $result['username'];?>'><br>
            Password: <input type = "text" name = "Password" value = '<?php echo $result['password'];?>'><br>
            Measured Date: <input type = "text" name = "date" value = '<?php echo $result['date'];?>'><br>
            Weight: <input type = "text" name = "weight" value = '<?php echo $result['weight'];?>'><br>
            Chest: <input type = "text" name = "chest" value = '<?php echo $result['chest'];?>'><br>
            Hips: <input type = "text" name = "hips" value = '<?php echo $result['hips'];?>'><br>
            <input type = "submit" value = "Edit">
        </form>
        <?php
    // }

    $username = $_GET[$result['Username']];
    $date = $_GET[$result['Date']];
    $weight = $_GET[$result["Weight"]];
    $waist = $_GET[$result["Waist"]];
    $chest = $_GET[$result["Chest"]];
    $hips = $_GET[$result["Hips"]];

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
                <button><a href = "profile-client.php">Go Back</button>
            </div>
            <div id = headerImage>
                <!-- I'm thinking about putting it in for design,
                so should I make it possible for users to upload it themselves? Or any image?
                <img src = "./.png" alt = " " title = " " width = " "> -->
            </div>
        </header>
        <div id = "measurement">
            <ul> <!-- different values for different users -->
                <li id = "date"><?php $date ?></li>
                <li id = "weight">Weight : <?php $weight ?></li>
                <li id = "waist">Waist : <?php $waist ?></li>
                <li id = "chest">Chest : <?php $chest ?></li>
                <li id = "hips">Hips : <?php $hips ?></li>
            </ul>
        </div>
        <footer>
            Copyright 2022. Fitness Tracker All rights reserved.
        </footer>
        <script language = "javascript">
            
        </script>
    </body>
</html>