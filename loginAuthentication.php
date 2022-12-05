<?php
    include('connection.php');

    $username = _POST['username'];
    $password = _POST['password'];

    $result = mysqli_query($connection, "SELECT * FROM user WHERE Username = '".$username."' AND PASSWORD = '".$password."'");
    $count = mysqli_num_rows($result);

    if ($count == 1) echo "Login successful";
    else echo "Login failed. Username/password combination not found.";
?>