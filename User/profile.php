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
body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
color:#eaf0ff;
}

/* layout */
.container{
width:92%;
max-width:1300px;
margin:70px auto 90px;
}

/* GLASS SQUARE CARD */
.glass-card{
background:rgba(255,255,255,0.12);
backdrop-filter:blur(18px);
border-radius:14px; /* square feel */
padding:28px;
margin-bottom:32px;
box-shadow:
0 18px 45px rgba(0,0,0,0.6),
inset 0 0 0 1px rgba(255,255,255,0.15);
transition:.35s ease;
}
.glass-card:hover{
transform:translateY(-4px);
box-shadow:
0 30px 70px rgba(0,0,0,0.75),
inset 0 0 0 1px rgba(140,180,255,0.45);
}

/* headings */
.glass-card h2{
margin:0 0 18px;
font-size:22px;
background:linear-gradient(90deg,#9bb6ff,#e0e7ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* grid */
.grid-2{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:26px;
}
.grid-3{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:26px;
}

/* inputs */
input{
width:100%;
padding:12px;
border-radius:10px;
border:none;
margin-bottom:12px;
background:rgba(0,0,0,0.55);
color:white;
box-shadow:inset 0 0 0 1px rgba(255,255,255,0.15);
}

/* button */
button{
background:linear-gradient(135deg,#7aa2ff,#4f7cff);
border:none;
padding:10px 22px;
border-radius:22px;
color:white;
cursor:pointer;
box-shadow:0 12px 30px rgba(122,162,255,0.6);
}

/* stats card */
.stat-card{
background:rgba(0,0,0,0.45);
border-radius:14px;
padding:26px;
text-align:center;
box-shadow:inset 0 0 0 1px rgba(255,255,255,0.12);
}
.stat-card h3{
margin:0;
font-size:30px;
color:#7aa2ff;
}

/* map */
.map{
width:100%;
height:340px;
border-radius:14px;
border:none;
box-shadow:0 20px 50px rgba(0,0,0,0.8);
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