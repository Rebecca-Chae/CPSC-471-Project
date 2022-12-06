<?php 
    session_start();
    $connection = mysqli_connect("localhost", "root", "", "fitnessTracker");
    if ($connection->connect_error)
    {
        die();
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if(isset($_SESSION['c_username'])){
        $client = $_SESSION['c_username'];
    }
    if(isset($_SESSION['p_username'])){
        $username = $_SESSION['p_username'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['c_username'])){
            $client = $_POST['c_username'];
            $_SESSION['c_username'] = $client;
        }
        if(isset($_POST['p_username'])){
            $username = $_POST['p_username'];
            $_SESSION['p_username'] = $username;
        }
        if(isset($_POST['addFb'])){
            $to_add = $_POST['addFb'];
            $q = "UPDATE Hires SET Feedback = '".$to_add."' where Professional_Username = '".$username."' AND Client_Username = '".$client."'";
            $a = mysqli_query($connection, $q);
        }
        if(isset($_POST['addFd'])){
            $to_add = $_POST['addFd'];
            $q = "INSERT INTO recommended_meal_plan (FoodID, Client_Username, Nutritionists_Username) values('".$to_add."','".$client."', '".$username."')";
            $a = mysqli_query($connection, $q);
        }
        
    }
    echo hello;
    if (isset($_GET["deletedFd"])){
        $toRemove = rtrim($_GET["deletedFd"], " X");
        $q = "DELETE FROM recommended_meal_plan where FoodId = '".$toRemove."' and Client_Username = '".$client."' and  Nutritionists_Username = '".$username."'";
        $a = mysqli_query($connection, $q);
        echo $a;
    }
    $q = "SELECT * FROM Body_Measurement where Username = '".$client."'";
    $b_measure = mysqli_query($connection, $q);
    $row = mysqli_fetch_array($b_measure);
    $q = "SELECT * FROM Hires where Professional_Username = '".$username."' And Client_Username = '".$client."'";
    $res = mysqli_query($connection, $q);
    $r = mysqli_fetch_array($res);
    $clients_meas = "Client: " . $client. "<br />Date: ".  $row['Date'] . "<br /> Weight: ". $row ['Weight'] . "lb <br />Hips: ". $row['Hips'] . "cm <br />Hips: ". $row['Waist'] ."cm <br />Chest: ". $row['Chest'] . "cm" . "<br \> Previous Feedback: ". $r['Feedback'];
?>
<!DOCTYPE html>
    <head>
        <link href = "./style.css?ver=1" rel = "stylesheet">
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>Fitness Tracker</title>
    </head>
    <body>
        <button id = "back"> <a href = "professionalN.php">goback</a></button>
        <div>
            <h4><?php echo $clients_meas; ?><h4>
        <form action = "feedbackNutrition.php" method = "post">
            <input type = 'hidden' name = 'c_username' value = <?php echo $client ?>>
            <input type = "text" name = "addFb" id = "addFb" style = "width: 20vw; height: 1.2rem;">
            <input id = "addFbButton" type = "submit" value = "Submit Feedback">
        </form>
        </div>
        <div class = "recRoutine">
            <table>
            <tr><h3> Recommended Meal Plan: </h3></tr>
            <tr>
                <ul>
            <?php 
                $q = "SELECT * FROM recommended_meal_plan where Client_Username = '".$client."' and  Nutritionists_Username = '".$username."'" ;
                $res = mysqli_query($connection, $q);
                while($row = mysqli_fetch_array($res)){
                    $exercise = $row['FoodID'];
                    echo "<li class = 'Exercise'>$exercise </li>";
                }
            ?>
                </ul>
            </tr>
            </table>
            <form action = "feedbackNutrition.php" method = "post">
                <input type = 'hidden' name = 'c_username' value = <?php echo $client ?>>
                <input type = "text" name = "addFd" id = "addRecEx" style = "width: 20vw; height: 1.2rem;">
                <input id = "addExerciseButton" type = "submit" value = "Add To Routine">
            </form>
        </div>

<script type = "text/javascript">
    const list = document.getElementsByClassName("Exercise");
    for(let i = 0; i < list.length; i++){
        let del = document.createElement('button');
        list[i].appendChild(del);
        del.innerText = "X";
        del.style.color = "white";
        del.style.fontSize = "0.5rem";
        del.style.fontWeight = "bold";
        del.style.border = "none";
        del.style.borderRadius = "5px";
        del.style.backgroundColor = "rgb(41, 52, 130)";
        del.style.padding = "5px";
        del.style.cursor = "pointer";
        del.addEventListener("click", function deleteList(e){
            location.replace(`feedbackNutrition.php?deletedFd=${list[i].innerText}`);
        });
    }
</script>
</body>
</html>
