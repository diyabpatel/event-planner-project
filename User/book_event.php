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

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE event_id=$event_id"));
$packages = mysqli_query($conn,"SELECT * FROM packages WHERE event_id=$event_id");

if(isset($_POST['book'])){

    $package_id     = (int)$_POST['package_id'];
    $venue_id       = (int)$_POST['venue_id'];
    $decoration_id  = (int)$_POST['decoration_id'];
    $seat_id        = (int)$_POST['seat_id'];
    $capacity       = (int)$_POST['capacity'];
    $event_date     = $_POST['event_date'];

    $food_ids = isset($_POST['food_id']) ? $_POST['food_id'] : [];
    $coverage_ids = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : [];

    $total = 0;

    $venue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id"));
    $total += $venue['price'];

    $decor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id"));
    $total += $decor['price'];

    $seat = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id"));
    $total += $seat['price'] * $capacity;

    foreach($food_ids as $fid){
        $f = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM food WHERE food_id=$fid"));
        $total += $f['price'] * $capacity;
    }

    foreach($coverage_ids as $cid){
        $c = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$cid"));
        $total += $c['price'];
    }

  $_SESSION['booking_data'] = [
    "user_id"=>$user_id,
    "event_id"=>$event_id,
    "package_id"=>$package_id,
    "event_date"=>$event_date,
    "capacity"=>$capacity,
    "total_price"=>$total,
    "food_ids"=>implode(',', $food_ids),         // 🔥 ADD
    "coverage_ids"=>implode(',', $coverage_ids)  // 🔥 ADD
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

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(135deg,#0f172a,#020617);
color:white;
}

.container{
max-width:1100px;
margin:auto;
padding:40px 20px;
}

h2{
text-align:center;
font-size:32px;
margin-bottom:30px;
}

/* PACKAGES */
.packages{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
}

.package{
padding:30px;
border-radius:18px;
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
text-align:center;
cursor:pointer;
transition:0.4s;
position:relative;
}

.package:hover{
transform:translateY(-8px);
border-color:#6366f1;
}

.package.active{
background:linear-gradient(135deg,#6366f1,#8b5cf6);
box-shadow:0 0 25px rgba(99,102,241,0.6);
}

.package.active::after{
content:"✔";
position:absolute;
top:10px;
right:15px;
}

/* FORM */
form{
margin-top:40px;
padding:30px;
border-radius:18px;
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
}

/* INPUT */
.input{
margin-bottom:18px;
}

.input label{
font-size:13px;
opacity:0.7;
}

.input input,
.input select{
width:100%;
padding:12px;
margin-top:6px;
border-radius:8px;
border:none;
background:#020617;
color:white;
border:1px solid rgba(255,255,255,0.1);
}

/* VENUE GRID */
.venue-grid{
display:grid;
grid-template-columns:repeat(3,1fr); /* same as seats */
gap:15px;
margin-top:10px;
}

/* VENUE CARD */
.venue-card{
background:#020617;
border:1px solid rgba(255,255,255,0.1);
border-radius:14px;
padding:15px;
cursor:pointer;
transition:0.3s;
text-align:center;
position:relative;
}

/* IMAGE SQUARE 🔥 */
.venue-card img{
width:100%;
aspect-ratio:1/1;   /* 🔥 main trick */
object-fit:cover;
border-radius:10px;
margin-bottom:10px;
}

/* HOVER */
.venue-card:hover{
transform:scale(1.05);
border-color:#6366f1;
}

/* ACTIVE */
.venue-card.active{
border:2px solid #6366f1;
box-shadow:0 0 20px rgba(99,102,241,0.6);
}

.venue-card.active::after{
content:"✔";
position:absolute;
top:10px;
right:10px;
background:#6366f1;
padding:4px 8px;
border-radius:6px;
}

/* SEAT GRID */
.seat-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:15px;
margin-top:10px;
}

.seat-card{
background:#020617;
border:1px solid rgba(255,255,255,0.1);
border-radius:14px;
padding:20px;
cursor:pointer;
transition:0.3s;
text-align:center;
position:relative;
}

.seat-card:hover{
transform:scale(1.05);
border-color:#6366f1;
}

.seat-card.active{
border:2px solid #6366f1;
box-shadow:0 0 20px rgba(99,102,241,0.6);
}

.seat-card.active::after{
content:"✔";
position:absolute;
top:10px;
right:10px;
background:#6366f1;
padding:4px 8px;
border-radius:6px;
}

.seat-info h4{
margin-bottom:8px;
}

/* CHECKBOX */
.checkbox{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:10px;
}

.checkbox label{
display:flex;
align-items:center;
gap:8px;
background:#020617;
padding:10px;
border-radius:8px;
cursor:pointer;
border:1px solid rgba(255,255,255,0.1);
}

/* BUTTON */
button{
width:100%;
padding:14px;
border:none;
border-radius:10px;
background:linear-gradient(135deg,#6366f1,#8b5cf6);
cursor:pointer;
}

button:hover{
transform:scale(1.03);
}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2><?= $event['event_name'] ?></h2>

<!-- PACKAGES -->
<div class="packages">
<?php while($row=mysqli_fetch_assoc($packages)){ ?>
<div class="package <?= ($package_id==$row['package_id'])?'active':'' ?>"
onclick="location='?event_id=<?= $event_id ?>&package_id=<?= $row['package_id'] ?>'">
<?= $row['package_name'] ?>
</div>
<?php } ?>
</div>

<?php if($package_id){ ?>

<form method="POST">

<input type="hidden" name="package_id" value="<?= $package_id ?>">
<input type="hidden" name="venue_id" id="venueInput" required>

<div class="input">
<label>Capacity</label>
<input type="number" name="capacity" required>
</div>

<!-- VENUE CARDS -->
<div class="input">
<label>Select Venue</label>

<div class="venue-grid">
<?php
$q=mysqli_query($conn,"SELECT * FROM venues WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q)){

$folder = strtolower(str_replace(" ", "_", $event['event_name']));
$img = "/event-planner-project/uploads/images/venues/".$folder."/".$r['venue_image'];

echo "
<div class='venue-card' onclick='selectVenue(this, {$r['venue_id']})'>
<img src='$img'>
<div class='venue-info'>
<h4>{$r['venue_name']}</h4>
<p>₹{$r['price']}</p>
</div>
</div>
";
}
?>
</div>
</div>

<div class="input">
<label>Decoration</label>
<select name="decoration_id" required>
<option value="" disabled selected>-- Select Decoration --</option>
<?php
$q=mysqli_query($conn,"SELECT * FROM decorations WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q)){
echo "<option value='{$r['decoration_id']}'>{$r['decoration_name']} ₹{$r['price']}</option>";
}
?>
</select>
</div>

<div class="input">
<label>Seats</label>

<input type="hidden" name="seat_id" id="seatInput" required>

<div class="seat-grid">
<?php
$q=mysqli_query($conn,"SELECT * FROM seats WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q)){


$img = isset($r['seat_images']) ? "/event-planner-project/uploads/images/seats/".$r['seat_images'] : "";

echo "
<div class='seat-card' onclick='selectSeat(this, {$r['seat_id']})'>

".($img ? "<img src='$img'>" : "")."

<div class='seat-info'>
<h4>{$r['seat_type']}</h4>
<p>₹{$r['price']} per seat</p>
</div>

</div>
";
}
?>
</div>
</div>

<div class="input">
<label>Food</label>
<div class="checkbox">
<?php
$q=mysqli_query($conn,"SELECT * FROM food WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q)){
echo "<label><input type='checkbox' name='food_id[]' value='{$r['food_id']}'> <span>{$r['menu']}</span></label>";
}
?>
</div>
</div>

<div class="input">
<label>Coverage</label>
<div class="checkbox">
<?php
$q=mysqli_query($conn,"SELECT * FROM coverage WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q)){
echo "<label><input type='checkbox' name='coverage_id[]' value='{$r['coverage_id']}'> <span>{$r['coverage_type']}</span></label>";
}
?>
</div>
</div>

<div class="input">
<label>Date</label>
<input type="date" name="event_date" required>
</div>

<button name="book">Book Now</button>

</form>

<?php } ?>

</div>

<script>
function selectVenue(el, id){
document.querySelectorAll(".venue-card").forEach(card=>{
card.classList.remove("active");
});
el.classList.add("active");
document.getElementById("venueInput").value = id;
}
</script>

<script>
function selectSeat(el, id){
document.querySelectorAll(".seat-card").forEach(card=>{
card.classList.remove("active");
});
el.classList.add("active");
document.getElementById("seatInput").value = id;
}
</script>

</body>
</html>