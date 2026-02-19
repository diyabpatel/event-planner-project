<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user */
$user_query = mysqli_query($conn,"SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

/* Booking History */
$booking_query = mysqli_query($conn,"
SELECT b.*, e.event_name 
FROM bookings b
JOIN events e ON b.event_id = e.event_id
WHERE b.user_id='$user_id'
ORDER BY b.booking_id DESC
");

/* Change Password */
if(isset($_POST['change_pass'])){
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];

    if($old == $user['password']){
        mysqli_query($conn,"UPDATE users SET password='$new' WHERE user_id='$user_id'");
        echo "<script>alert('Password Changed Successfully');</script>";
    } else {
        echo "<script>alert('Old Password Incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>User Profile</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
}

/* Container */
.container{
    width:85%;
    margin:40px auto;
}

/* Card */
.card{
    background:rgba(255,255,255,0.08);
    padding:25px;
    border-radius:20px;
    margin-bottom:30px;
    backdrop-filter:blur(10px);
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:12px;
    text-align:left;
}
th{
    border-bottom:1px solid rgba(255,255,255,0.3);
}

/* Inputs */
input{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:none;
    margin-bottom:10px;
}

button{
    background:linear-gradient(45deg,#00c6ff,#0072ff);
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:20px;
    cursor:pointer;
    margin-right:10px;
}

.map{
    width:100%;
    height:350px;
    border-radius:15px;
    border:0;
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>


<div class="container">

<!-- PROFILE -->
<div class="card">
    <h2>👤 User Profile</h2>
    <p><b>Name:</b> S.S. Agrawal College</p>
    <p><b>Email:</b> <?php echo $user['email']; ?></p>
</div>

<!-- LOCATION -->
<div class="card">
    <h2>📍 College Location (S.S. Agrawal – Navsari)</h2>

    <!-- Default College Map -->
    <iframe id="mapFrame" class="map"
    src="https://www.google.com/maps?q=S.S.%20Agrawal%20College%20Navsari&output=embed">
    </iframe>

    <br><br>

    <button onclick="getLocation()">📡 Show My Live Location</button>
    <button onclick="shareLocation()">📤 Share This Location</button>
</div>

<!-- BOOKING HISTORY -->
<div class="card">
    <h2>📅 Booking History</h2>
    <table>
        <tr>
            <th>Event</th>
            <th>Date</th>
            <th>Total Price</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($booking_query)){ ?>
        <tr>
            <td><?php echo $row['event_name']; ?></td>
            <td><?php echo $row['event_date']; ?></td>
            <td>₹<?php echo $row['total_price']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<!-- CHANGE PASSWORD -->
<div class="card">
    <h2>🔐 Change Password</h2>
    <form method="POST">
        <input type="password" name="old_pass" placeholder="Enter Old Password" required>
        <input type="password" name="new_pass" placeholder="Enter New Password" required>
        <button type="submit" name="change_pass">Change Password</button>
    </form>
</div>

</div>

<script>
let currentLat = null;
let currentLon = null;

function getLocation(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){

            currentLat = position.coords.latitude;
            currentLon = position.coords.longitude;

            document.getElementById("mapFrame").src =
            "https://www.google.com/maps?q=" + currentLat + "," + currentLon + "&output=embed";

        });
    } else {
        alert("Geolocation not supported");
    }
}

function shareLocation(){

    if(currentLat === null || currentLon === null){
        alert("First click 'Show My Live Location'");
        return;
    }

    let locationURL = "https://www.google.com/maps?q=" + currentLat + "," + currentLon;

    if(navigator.share){
        navigator.share({
            title:"My Live Location",
            text:"Here is my location:",
            url:locationURL
        });
    }else{
        prompt("Copy this link:", locationURL);
    }
}
</script>

</body>
</html>
