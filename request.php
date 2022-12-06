<?php
    session_start();

    $secret = '';

    $connection = mysqli_connect("localhost", "root", $secret, "database");
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    if(isset($_SESSION['c_username'])){
        $client = $_SESSION['c_username'];
    }

    if(isset($_SESSION['p_username'])){
        $prof = $_SESSION['p_username'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['c_username'])){
            $client = $_POST['c_username'];
            $_SESSION['c_username'] = $client;
        }
        
        if(isset($_POST['p_username'])){
            $prof = $_POST['p_username'];
            $_SESSION['p_username'] = $prof;
        }
    }

    date_default_timezone_set('Canada/Mountain');
    $today = date('Y-m-d', time());
    $row = mysqli_query($connection, "SELECT * FROM Professional’s_Times_Available WHERE Username = '".$prof."'");
    while ($availables = mysqli_fetch_array($row)){
        if (strtotime($availables['Date']) > strtotime($today)){
            $sql = "INSERT INTO Appointments (Professional_Username, Time, Date, Client_Username) VALUES ('".$prof."','".$availables['Time']."','".$availables['Date']."','".$client."')";
            mysqli_query($connection, $sql);
            break;
        }
    }

    exit('<script>location.replace("profile-client.php")</script>');
?>