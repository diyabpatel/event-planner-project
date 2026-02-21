<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* USER DATA */
$userQ = mysqli_query($conn,"SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($userQ);

/* EDIT PROFILE */
if(isset($_POST['save_profile'])){
    $name  = $_POST['name'];
    $phone = $_POST['phone'];

    mysqli_query($conn,"
        UPDATE users 
        SET name='$name', phone='$phone' 
        WHERE user_id='$user_id'
    ");
    header("Location: profile.php");
    exit();
}

/* CHANGE PASSWORD */
if(isset($_POST['change_pass'])){
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];

    if($old == $user['password']){
        mysqli_query($conn,"UPDATE users SET password='$new' WHERE user_id='$user_id'");
        echo "<script>alert('Password changed successfully');</script>";
    }else{
        echo "<script>alert('Old password incorrect');</script>";
    }
}

/* PAYMENT STATS */
$stats = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
SUM(total_price) AS total_spent,
SUM(advance_paid) AS advance_paid,
SUM(remaining_amount) AS pending_amt
FROM bookings WHERE user_id='$user_id'
"));

$totalSpent  = isset($stats['total_spent']) ? $stats['total_spent'] : 0;
$advancePaid = isset($stats['advance_paid']) ? $stats['advance_paid'] : 0;
$pendingAmt  = isset($stats['pending_amt']) ? $stats['pending_amt'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>User Profile</title>

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
box-sizing:border-box;
}

body{
margin:0;
font-family:'Poppins', sans-serif;
background:
linear-gradient(135deg,#1e3c72,#2a5298);
color:#f1f5ff;
}

/* layout */
.container{
width:92%;
max-width:1250px;
margin:70px auto 90px;
}

/* GLASS CARD */
.glass-card{
background:rgba(255,255,255,0.12);
backdrop-filter:blur(20px);
border-radius:18px;
padding:30px;
margin-bottom:30px;
box-shadow:
0 20px 60px rgba(0,0,0,0.4);
transition:0.35s ease;
}

.glass-card:hover{
transform:translateY(-6px);
box-shadow:
0 30px 80px rgba(0,0,0,0.55);
}

/* headings */
.glass-card h2{
margin:0 0 20px;
font-size:22px;
font-weight:500;
color:#e0e7ff;
}

/* grids */
.grid-2{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

.grid-3{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:30px;
}

/* inputs */
input{
width:100%;
padding:14px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.25);
margin-bottom:14px;
background:rgba(255,255,255,0.15);
color:white;
font-size:14px;
font-family:'Poppins', sans-serif;
}

input:focus{
outline:none;
border-color:#93c5fd;
box-shadow:0 0 0 2px rgba(147,197,253,0.4);
}

/* buttons */
button{
background:linear-gradient(135deg,#3b82f6,#2563eb);
border:none;
padding:12px 26px;
border-radius:30px;
color:white;
font-weight:500;
cursor:pointer;
transition:0.3s;
box-shadow:0 12px 35px rgba(37,99,235,0.5);
}

button:hover{
transform:translateY(-4px);
box-shadow:0 20px 50px rgba(37,99,235,0.7);
}

/* stats */
.stat-card{
background:rgba(255,255,255,0.15);
border-radius:18px;
padding:30px;
text-align:center;
transition:0.3s;
}

.stat-card:hover{
background:rgba(147,197,253,0.25);
transform:translateY(-5px);
}

.stat-card h3{
margin:0;
font-size:34px;
font-weight:600;
color:#93c5fd;
}

/* map */
.map{
width:100%;
height:340px;
border-radius:18px;
border:none;
box-shadow:0 20px 60px rgba(0,0,0,0.6);
margin-top:10px;
}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<!-- PROFILE VIEW -->
<div class="glass-card">
<h2>👤 Profile</h2>
<p><b>Email:</b> <?php echo $user['email']; ?></p>
<p><b>Phone:</b> <?php echo isset($user['phone'])?$user['phone']:'Not Added'; ?></p>
</div>

<!-- EDIT PROFILE + CHANGE PASSWORD -->
<div class="grid-2">

<div class="glass-card">
<h2>✏️ Edit Profile</h2>
<form method="POST">
<input type="text" name="name" value="<?php echo isset($user['name'])?$user['name']:''; ?>" placeholder="Name">
<input type="text" name="phone" value="<?php echo isset($user['phone'])?$user['phone']:''; ?>" placeholder="Phone">
<button name="save_profile">Save Profile</button>
</form>
</div>

<div class="glass-card">
<h2>🔐 Change Password</h2>
<form method="POST">
<input type="password" name="old_pass" placeholder="Old Password" required>
<input type="password" name="new_pass" placeholder="New Password" required>
<button name="change_pass">Change Password</button>
</form>
</div>

</div>

<!-- PAYMENT SUMMARY -->
<div class="glass-card">
<h2>💳 Payment Summary</h2>
<div class="grid-3">
<div class="stat-card">
<h3 id="spent">0</h3>
<p>Total Spent</p>
</div>
<div class="stat-card">
<h3 id="advance">0</h3>
<p>Advance Paid</p>
</div>
<div class="stat-card">
<h3 id="pending">0</h3>
<p>Pending Amount</p>
</div>
</div>
</div>

<!-- MAP -->
<div class="glass-card">
<h2>📍 Location</h2>
<iframe id="mapFrame" class="map"
src="https://www.google.com/maps?q=S.S.%20Agrawal%20College%20Navsari&output=embed"></iframe>
<br><br>
<button onclick="getLocation()">View Location</button>
<button onclick="shareLocation()">Share Location</button>
</div>

</div>

<script>
/* animated counters */
function animate(id,target){
let el=document.getElementById(id);
let count=0;
let step=Math.ceil(target/40);
let i=setInterval(()=>{
count+=step;
if(count>=target){
count=target;
clearInterval(i);
}
el.innerText="₹"+count;
},25);
}
animate("spent",<?php echo (int)$totalSpent; ?>);
animate("advance",<?php echo (int)$advancePaid; ?>);
animate("pending",<?php echo (int)$pendingAmt; ?>);

/* location */
let lat=null,lon=null;
function getLocation(){
if(navigator.geolocation){
navigator.geolocation.getCurrentPosition(function(pos){
lat=pos.coords.latitude;
lon=pos.coords.longitude;
document.getElementById("mapFrame").src =
"https://www.google.com/maps?q="+lat+","+lon+"&output=embed";
});
}
}
function shareLocation(){
if(lat==null){
alert("Click View Location first");
return;
}
let url="https://www.google.com/maps?q="+lat+","+lon;
if(navigator.share){
navigator.share({title:"My Location",url:url});
}else{
prompt("Copy this link:",url);
}
}
</script>

</body>
</html>