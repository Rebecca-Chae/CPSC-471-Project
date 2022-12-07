<?php
    include('connection.php');
    
    // Ensure that user did not input empty username or password
    if (empty($_POST['newusername']) || empty($_POST['password1'])) {
        exit("Username and password cannot be empty");
    }
    if ($_POST['password1'] != $_POST['password2']) {
        exit('Passwords do not match. Make sure you typed them in correctly.');
    }

    $stmt = $connection -> prepare('SELECT * FROM User WHERE Username = ?');
    $stmt -> bind_param('s', $_POST['newusername']);
    $stmt -> execute();
    $stmt -> store_result();
    if ($stmt -> num_rows > 0) {
        exit("Username already exists. Choose a different one.");
    } 
    $stmt -> close();

    $stmt = $connection -> prepare('INSERT INTO User (username, password) VALUES (?,?)');
    $stmt -> bind_param('ss', $_POST['newusername'], $_POST['password1']);
    $stmt -> execute();

    date_default_timezone_set('Canada/Mountain');
    $today = date('Y-m-d', time());
    $temp = 0.0;
    $stmt = $connection -> prepare('INSERT INTO Body_Measurement (Username, Date, Weight, Waist, Chest, Hips) VALUES (?,?,?,?,?,?)');
    $stmt -> bind_param('ssdddd', $_POST['newusername'], $today, $temp, $temp, $temp, $temp);
    $stmt -> execute();

    if ($_POST['class'] == "client"){
        $stmt = $connection -> prepare('INSERT INTO Client (username) VALUES (?)');
        $stmt -> bind_param('s', $_POST['newusername']);
        $stmt -> execute();
    } else {
        $stmt = $connection -> prepare('INSERT INTO Professional (username) VALUES (?)');
        $stmt -> bind_param('s', $_POST['newusername']);
        $stmt -> execute();
        if ($_POST['class'] == "personal_trainer"){
            $stmt = $connection -> prepare('INSERT INTO Personal_Trainer (username) VALUES (?)');
            $stmt -> bind_param('s', $_POST['newusername']);
            $stmt -> execute();
        } else if ($_POST['class'] == "nutritionist"){
            $stmt = $connection -> prepare('INSERT INTO Nutritionist (username) VALUES (?)');
            $stmt -> bind_param('s', $_POST['newusername']);
            $stmt -> execute();
        }
    }
    
    exit('<script>location.replace("Login.html")</script>');
?>