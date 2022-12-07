<?php 
    session_start();

    $secret = '';

    $connection = mysqli_connect("localhost", "root", $secret, "database");
    if (mysqli_connect_error($connection)) echo "Failed to connect to MySQL: " . mysqli_connect_error();

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
    
    if (isset($_GET["deletedFd"])){
        $toRemove = rtrim($_GET["deletedFd"], " X");
        $q = "DELETE FROM recommended_meal_plan where FoodId = '".$toRemove."' and Client_Username = '".$client."' and  Nutritionists_Username = '".$username."'";
        $a = mysqli_query($connection, $q);
    }

    $q = "SELECT * FROM Body_Measurement where Username = '".$client."'";
    $b_measure = mysqli_query($connection, $q);
    $row = mysqli_fetch_array($b_measure);
    $q = "SELECT * FROM Hires where Professional_Username = '".$username."' And Client_Username = '".$client."'";
    $res = mysqli_query($connection, $q);
    $r = mysqli_fetch_array($res);
    $clients_meas = "Client Name: ".$client."<br/><br/>Date: ".$row['Date']."<br/><br/>Weight: ".$row['Weight']."lb<br/><br/>Hips: ".$row['Hips']."cm<br/><br/>Waist: ".$row['Waist']."cm<br/><br/>Chest: ".$row['Chest']."cm<br/><br/>Previous Feedback: ".$r['Feedback'];
?>

<!DOCTYPE html>
    <head>
        <link href = "./style1.css?ver=1" rel = "stylesheet">
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>Fitness Tracker</title>
    </head>
    <body>
        <header>
            <div id = "username">
                Fitness Tracker
            </div>
            <div id = "option" style = "float: right;">
                <form action = "profile-nutritionist.php" method = "post">
                    <input type = "hidden" name = "username" value = <?php echo $username;?>>
                    <input id = "b1" type = "submit" value = "Go Back">
                </form>
            </div>
        </header>
        <div id = "toClient">
            <table>
                <tr>
                    <td>
                    <h3>• Client's Body Measurement</h3>
                        <?php echo $clients_meas; ?>
                        <form action = "feedbackN.php" method = "post">
                            <input type = 'hidden' name = 'c_username' value = <?php echo $client ?>>
                            <input type = "text" name = "addFb" id = "addFb" style = "width: 250px; height: 30px;">
                            <input id = "addFeedback" type = "submit" value = "Submit Feedback">
                        </form>
                    </td>
                    <td>
                        <h3>• Recommended Meal Plan</h3>
                        <ol>
                            <?php 
                                $q = "SELECT * FROM recommended_meal_plan where Client_Username = '".$client."' And  Nutritionists_Username = '".$username."'";
                                $res = mysqli_query($connection, $q);
                                while($row = mysqli_fetch_array($res)){
                                    $food = $row['FoodID'];
                                    echo "<li class = 'meal'>$food </li>";
                                }
                            ?>
                        </ol>
                        <form action = "feedbackN.php" method = "post">
                            <input type = 'hidden' name = 'c_username' value = <?php echo $client ?>>
                            <input type = "text" name = "addFd" id = "addRecEx" style = "width: 250px; height: 30px;">
                            <input id = "addRoutine" type = "submit" value = "Add To Diet">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <script type = "text/javascript">
            const list = document.getElementsByClassName("meal");
            for(let i = 0; i < list.length; i++){
                let del = document.createElement('button');
                list[i].appendChild(del);
                del.innerText = "X";
                del.style.float = "right";
                del.style.color = "white";
                del.style.fontSize = "0.5rem";
                del.style.fontWeight = "bold";
                del.style.border = "none";
                del.style.borderRadius = "5px";
                del.style.backgroundColor = "rgb(41, 52, 130)";
                del.style.padding = "5px";
                del.style.cursor = "pointer";
                del.addEventListener("click", function deleteList(e){
                    location.replace(`feedbackN.php?deletedFd=${list[i].innerText}`);
                });
            }
        </script>
    </body>
</html>