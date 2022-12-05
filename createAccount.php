<?php
    include('connection.php');
?>

<!DOCTYPE html>
<html lang = "en-US">
    <head>
        <link href = "./style.css?ver=1" rel = "stylesheet"> <!-- We can put an externally made css file in here -->
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>Fitness Tracker</title>
        <style>
        h1 {
            text-align: center;
          }</style>
    </head>
    <body>
        <h1> Fitness tracker </h1>
        <h2> Create Account</h2>

        <form action="/action_page.php">
            <label for="username"> Enter a username: </label>
            <input type="text" id="newusername" name="newusername" placeholder="Type in your username here" size = 26><br><br>
            <label for="newpassword1">Enter a password: </label>
            <input type="password" id="newpassword1" name="password" placeholder="Type in your password here" size = 26><br><br>
            <label for="newpassword2">Re-enter a password: </label>
            <input type="password" id="newpassword2" name="password" placeholder="Type in your password here" size = 26><br><br>
            <input type="submit" id = "submitbutton" value="Create account">
        </form>
    
        <script>
            function validateCreateAccount()
                {
                    var user = document.getElementById('newusername').value;
                    var pass1 = document.getElementById('newpassword1').value;
                    var pass2 = document.getElementById('newpassword2').value;

                    if (user.length == "" || pass1.length == "" || pass2.length == "") {
                        alert("Username and password cannot be empty");
                        return false;
                    }

                    if (pass1 != pass2) {
                        alert("Passwords do not match.");
                    }

                    $result = mysqli_query($connection, "SELECT * FROM User WHERE Username = '".user."'");
                    $count = mysqli_num_rows($result);
                    
                    if ($count > 1) {
                        alert("Username already exists. Choose a different one.")
                    }
                }
        </script>

    </body>
</html>