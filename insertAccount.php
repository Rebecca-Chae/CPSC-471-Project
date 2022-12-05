<?php
    include('connection.php');

    $user = $_REQUEST['newusername'];
    $pass = $_REQUEST['newpassword1'];

    mysqli_query($connection, "INSERT INTO User (Username, Password) VALUES ($user, $pass");
?>

