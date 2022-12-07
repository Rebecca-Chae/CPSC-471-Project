<?php
    include('connection.php');

    // Ensure that user did not input empty username or password
    if (!isset($_POST['username'], $_POST['password1'])) {
        exit("Username and password cannot be empty");
    }
    if ($_POST['password1'] != $_POST['password2']) {
        exit('Passwords do not match. Make sure you typed them in correctly.');
    }

    $stmt = $connection -> prepare('SELECT * FROM User WHERE Username = ?');
    $stmt -> bind_param('s', $_POST['username']);
    $stmt -> execute();
    $stmt -> store_result();
    if ($stmt -> num_rows > 0) {
        exit("Username already exists. Choose a different one.");
    } 
    $stmt -> close();

    $stmt = $connection -> prepare('INSERT INTO User (username, password) VALUES (?,?)');
    $stmt -> bind_param('ss', $_POST['username'], $_POST['password1']);
    $stmt -> execute();
    echo "Registration succesful.";
?>

