<?php
    session_start();

    include('connection.php');

    // Ensure that user did not input empty username or password
    if (!isset($_POST['username'], $_POST['password'])) {
        exit("Username and password cannot be empty");
    }

    // Attempt to retrieve the user from the database
    // Search for the username/password combination the user inputted
    $stmt = $connection -> prepare('SELECT * FROM User WHERE Username = ? AND PASSWORD = ?');
    $stmt -> bind_param('ss', $_POST['username'], $_POST['password']);
    $stmt -> execute();
    $stmt -> store_result();

    // If the user exists
    if ($stmt -> num_rows > 0) {
        $stmt -> bind_result($username, $password);
        $stmt -> fetch();
        
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['username'] = $_POST['username'];
        echo 'Login successful';

        // Check if user is a professional. Otherwise, they are a client.
        $stmtprof = $connection -> prepare('SELECT * FROM Professional WHERE Username = ?');
        $stmtprof -> bind_param('s', $_POST['username']);
        $stmtprof -> execute();
        $stmtprof -> store_result();

        if ($stmtprof -> num_rows >0) {
            $_SESSION['userclass'] = 'Professional';
        } else {
            $_SESSION['userclass'] = 'Client';
        } 
        
    } else {
        echo 'Login failed. Username/password combination not found.';
    }

    $stmt -> close();
    $stmtprof -> close();
?>