<?php
    $secret = 'bruh';

    // IP, ID, Password, Name of DB
    $connection = mysqli_connect("localhost", "bruh", $secret, "database");
    
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

?>