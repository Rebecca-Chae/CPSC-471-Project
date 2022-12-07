<?php
    session_start();

    include('connection.php');

    // Ensure that user did not input empty username or password
    if (empty($_POST['username']) || empty($_POST['password'])) {
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

        // Check if user is a client. Otherwise, they are a professional.
        $stmtprof = $connection -> prepare('SELECT * FROM Client WHERE Username = ?');
        $stmtprof -> bind_param('s', $_POST['username']);
        $stmtprof -> execute();
        $stmtprof -> store_result();

        if ($stmtprof -> num_rows > 0) {
            $_SESSION['userclass'] = 'Client';
            exit('<script>location.replace("profile-client.php")</script>');
        } else {
            // Check if user is a trainer or a nutritionist
            $stmtprof = $connection -> prepare('SELECT * FROM Personal_Trainer WHERE Username = ?');
            $stmtprof -> bind_param('s', $_POST['username']);
            $stmtprof -> execute();
            $stmtprof -> store_result();
            if ($stmtprof -> num_rows > 0) {
                $_SESSION['userclass'] = 'Personal_Trainer';
                exit('<script>location.replace("profile-professional.php")</script>');
            } else{
                $_SESSION['userclass'] = 'Personal_Trainer';
                exit('<script>location.replace("profile-nutritionist.php")</script>');
            }
        } 
    } else {
        echo 'Login failed. Username/password combination not found.';
    }

    $stmt -> close();
    $stmtprof -> close();
?>