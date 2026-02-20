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
/* ===== BODY ===== */
body{
    margin:0;
    font-family:'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:linear-gradient(135deg,#0b0f1a,#121a2e,#1a2742);
    color:#eaeaff;
}

/* ===== CONTAINER ===== */
.container{
    width:85%;
    max-width:1200px;
    margin:55px auto 80px;
}

/* ===== CARD ===== */
.card{
    background:rgba(255,255,255,0.06);
    padding:30px;
    border-radius:22px;
    margin-bottom:35px;
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:
        0 30px 70px rgba(0,0,0,0.65),
        inset 0 0 0 1px rgba(255,255,255,0.06);
    transition:0.4s ease;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:
        0 45px 90px rgba(0,0,0,0.75),
        inset 0 0 0 1px rgba(122,162,255,0.3);
}

/* ===== HEADINGS ===== */
.card h2{
    margin-top:0;
    margin-bottom:18px;
    font-size:24px;
    font-weight:600;
    letter-spacing:0.6px;
    background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* ===== PROFILE TEXT ===== */
.card p{
    font-size:15px;
    line-height:1.7;
    margin:8px 0;
}

.card b{
    color:#9bb6ff;
    font-weight:500;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

th,td{
    padding:14px 12px;
    text-align:left;
    font-size:14px;
}

th{
    color:#9bb6ff;
    font-weight:500;
    border-bottom:1px solid rgba(255,255,255,0.15);
}

td{
    border-bottom:1px solid rgba(255,255,255,0.05);
}

tr:hover td{
    background:rgba(122,162,255,0.08);
}

/* ===== INPUTS ===== */
input{
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    border:none;
    margin-bottom:14px;
    background:rgba(0,0,0,0.45);
    color:#eaeaff;
    font-size:14px;
    box-shadow:inset 0 0 0 1px rgba(255,255,255,0.08);
}

input::placeholder{
    color:#b7b7d6;
}

/* ===== BUTTON ===== */
button{
    background:linear-gradient(135deg,#7aa2ff,#4f7cff);
    color:white;
    border:none;
    padding:12px 26px;
    border-radius:30px;
    cursor:pointer;
    font-size:14px;
    font-weight:500;
    margin-right:12px;
    transition:0.35s ease;
    box-shadow:0 12px 30px rgba(122,162,255,0.5);
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 40px rgba(122,162,255,0.7);
}

/* ===== MAP ===== */
.map{
    width:100%;
    height:360px;
    border-radius:18px;
    border:none;
    box-shadow:0 20px 50px rgba(0,0,0,0.6);
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .container{
        width:92%;
    }

    th,td{
        font-size:13px;
    }

    .card h2{
        font-size:22px;
    }
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
