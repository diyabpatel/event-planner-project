<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_GET['event_id'])){
    echo "Invalid Event";
    exit();
}

$event_id   = (int)$_GET['event_id'];
$package_id = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;

/* ================= EVENT ================= */

$event_query = mysqli_query($conn,"SELECT * FROM events WHERE event_id=$event_id");
$event = mysqli_fetch_assoc($event_query);

/* ================= PACKAGES ================= */

$packages = mysqli_query($conn,"SELECT * FROM packages WHERE event_id=$event_id");

/* ================= PACKAGE NAME → BASE ID ================= */

$base_package_id = 0;

if($package_id){

    $pkg = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT package_name FROM packages WHERE package_id=$package_id")
    );

    if($pkg){
        if($pkg['package_name']=="Basic")    $base_package_id = 1;
        if($pkg['package_name']=="Standard") $base_package_id = 2;
        if($pkg['package_name']=="Premium")  $base_package_id = 3;
    }

}

/* ================= BOOK EVENT ================= */

if(isset($_POST['book'])){

    $package_id     = (int)$_POST['package_id'];
    $venue_id       = (int)$_POST['venue_id'];
    $decoration_id  = (int)$_POST['decoration_id'];
    $seat_id        = (int)$_POST['seat_id'];
    $capacity       = (int)$_POST['capacity'];
    $event_date     = $_POST['event_date'];

    $food_ids     = isset($_POST['food_id']) ? $_POST['food_id'] : [];
    $coverage_ids = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : [];

    if($event_date < date("Y-m-d",strtotime("+5 days"))){
        echo "<script>alert('Booking allowed only after 5 days');history.back();</script>";
        exit();
    }

    $total = 0;

    $venue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id"));
    $total += $venue['price'];

    $decor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id"));
    $total += $decor['price'];

    $seat = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id"));
    $total += $seat['price'] * $capacity;

    foreach($food_ids as $fid){

        $food = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM food WHERE food_id=".(int)$fid));
        $total += $food['price'] * $capacity;

    }

    foreach($coverage_ids as $cid){

        $cover = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=".(int)$cid));
        $total += $cover['price'];

    }

    $_SESSION['booking_data'] = [

        "user_id"=>$user_id,
        "event_id"=>$event_id,
        "package_id"=>$package_id,
        "event_date"=>$event_date,
        "total_price"=>$total,
        "food_ids"=>implode(",",$food_ids),
        "coverage_ids"=>implode(",",$coverage_ids),
        "capacity"=>$capacity

    ];

    header("Location: payment_new.php");
    exit();

}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Book Event</title>

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
box-sizing:border-box;
}

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(rgba(15,23,42,0.8),rgba(15,23,42,0.85)),
url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;
min-height:100vh;
color:#e5e7eb;
}

.container{
width:1000px;
max-width:95%;
margin:50px auto;
padding:40px;
border-radius:20px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(16px);
box-shadow:0 20px 50px rgba(0,0,0,0.5);
}

h3{
text-align:center;
font-size:28px;
margin-bottom:30px;
}

.section-title{
margin-top:20px;
margin-bottom:8px;
font-weight:500;
}

select,input{
width:100%;
padding:13px;
border-radius:12px;
border:1px solid rgba(255,255,255,0.25);
background:rgba(255,255,255,0.12);
color:white;
font-size:14px;
}

select{
appearance:none;
}

select option{
color:#000;
background:#fff;
}

.package-cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:18px;
margin-bottom:30px;
}

.package-card{
padding:22px;
border-radius:16px;
text-align:center;
cursor:pointer;
background:rgba(255,255,255,0.1);
border:2px solid transparent;
transition:0.2s;
}

.package-card:hover{
transform:translateY(-3px);
border-color:#60a5fa;
}

.package-card.active{
border-color:#60a5fa;
background:rgba(96,165,250,0.25);
}

.checkbox-group{
background:rgba(255,255,255,0.1);
padding:16px;
border-radius:16px;
display:grid;
grid-template-columns:repeat(2,1fr);
gap:12px;
}

.checkbox-group label{
display:flex;
align-items:center;
gap:10px;
background:rgba(255,255,255,0.12);
padding:10px 12px;
border-radius:10px;
cursor:pointer;
transition:0.2s;
}

.checkbox-group label:hover{
background:rgba(255,255,255,0.2);
}

.checkbox-group input[type="checkbox"]{
width:16px;
height:16px;
cursor:pointer;
}

button{
width:100%;
margin-top:30px;
padding:15px;
border:none;
border-radius:14px;
background:linear-gradient(135deg,#3b82f6,#2563eb);
color:white;
font-size:16px;
cursor:pointer;
transition:0.2s;
}

button:hover{
transform:translateY(-2px);
box-shadow:0 10px 20px rgba(0,0,0,0.35);
}

@media(max-width:700px){

.package-cards{
grid-template-columns:1fr;
}

.checkbox-group{
grid-template-columns:1fr;
}

}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h3>Book Event: <?= $event['event_name'] ?></h3>

<div class="package-cards">

<?php
while($row=mysqli_fetch_assoc($packages)){

$active = ($package_id==$row['package_id']) ? "active" : "";

echo "
<div class='package-card $active'
onclick=\"location='?event_id=$event_id&package_id={$row['package_id']}'\">

<h3>{$row['package_name']}</h3>

</div>";
}
?>

</div>

<?php if($base_package_id){ ?>

<form method="POST">

<input type="hidden" name="package_id" value="<?= $package_id ?>">

<label class="section-title">Capacity</label>
<input type="number" name="capacity" min="1" required>

<label class="section-title">Select Venue</label>
<select name="venue_id" required>

<option value="">Select Venue</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM venues WHERE event_id=$event_id AND package_id=$package_id");

while($r=mysqli_fetch_assoc($q)){
echo "<option value='{$r['venue_id']}'>{$r['venue_name']} ₹{$r['price']}</option>";
}
?>

</select>

<label class="section-title">Select Decoration</label>

<select name="decoration_id" required>

<option value="">Select Decoration</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM decorations WHERE event_id=$event_id AND package_id=$package_id");

while($r=mysqli_fetch_assoc($q)){
echo "<option value='{$r['decoration_id']}'>{$r['decoration_name']} ₹{$r['price']}</option>";
}
?>

</select>

<label class="section-title">Select Seat</label>

<select name="seat_id" required>

<option value="">Select Seat</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM seats WHERE event_id=$event_id AND package_id=$package_id");

while($r=mysqli_fetch_assoc($q)){
echo "<option value='{$r['seat_id']}'>{$r['seat_type']} ₹{$r['price']}</option>";
}
?>

</select>

<label class="section-title">Select Food</label>

<div class="checkbox-group">

<?php
$q=mysqli_query($conn,"SELECT * FROM food WHERE event_id=$event_id AND package_id=$package_id");

while($r=mysqli_fetch_assoc($q)){
echo "<label><input type='checkbox' name='food_id[]' value='{$r['food_id']}'> {$r['menu']} ₹{$r['price']}</label>";
}
?>

</div>

<label class="section-title">Select Coverage</label>

<div class="checkbox-group">

<?php
$q=mysqli_query($conn,"SELECT * FROM coverage WHERE event_id=$event_id AND package_id=$package_id");

while($r=mysqli_fetch_assoc($q)){
echo "<label><input type='checkbox' name='coverage_id[]' value='{$r['coverage_id']}'> {$r['coverage_type']} ₹{$r['price']}</label>";
}
?>

</div>

<label class="section-title">Event Date</label>

<input type="date" name="event_date" min="<?= date('Y-m-d',strtotime('+5 days')) ?>" required>

<button type="submit" name="book">Book Now</button>

</form>

<?php } ?>

</div>

</body>
</html>