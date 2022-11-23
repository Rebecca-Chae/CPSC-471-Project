<?php
$connection = mysqli_connect("localhost", "root", "Password", "fitnessTracker");
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
 
 if (!mysqli_query($con,$sql))
  {
  //die('Error: e' . mysqli_error($con));
  }
  $q = "SELECT * FROM Appointments WHERE Professional_Username = $.username";
  $Appoints = $connection -> query($q);
  $q = "SELECT * FROM  hires WHERE Professional_Username = $.username";
  $Clients = $connection -> query($q);
?>

<html>
    <body>
      <style>
            table, th, td {
              border: 1px solid black;
            }
          </style>
        <div id = "username">
          <h1>NAME_OF_PROFESSIONAL</h1>
        </div> 
        <div style = "text-align: right">
          <button id = "editProfile"> <a href = "editProfile.php"> Edit Profile </a> </button>
          <button id = "logOut"> <a href = "logOut.php"> LogOut </a> </button>
        </div>
        <div>
          Your next Scheduled Appointment is on ___ with __
        </div>
        <div id = "Friends">
          <table style = "float: right" bordercolor = black>
            <tr>
              <th>Friends</th>
            </tr>
            <tr><td>Temp test</tr></td>
            <tr>
              <td>
                <button id = "addFriends"> Add Friends </button>
              </td>
            </tr>
          </table>
        </div>
        <div id = "clients">
          <table style = "float: right;" WIDTH = 200 bordercolor = black>
            <tr>
              <th>Clients</th>
            </tr>
          </table>
        </div>
    </body>
</html>
