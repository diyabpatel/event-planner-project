<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_GET['event_id']))
{
    echo "Invalid Event";
    exit();
}

$event_id = intval($_GET['event_id']);
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : "";


// fetch event
$event_query = "SELECT * FROM events WHERE event_id=$event_id";
$event_result = mysqli_query($conn,$event_query);
$event = mysqli_fetch_assoc($event_result);


// fetch packages
$packages = mysqli_query($conn,"SELECT * FROM packages WHERE event_id=$event_id");


// BOOKING BUTTON CLICK
if(isset($_POST['book']))
{

$package_id = $_POST['package_id'];
$venue_id = $_POST['venue_id'];
$decoration_id = $_POST['decoration_id'];
$seat_id = $_POST['seat_id'];
$capacity = $_POST['capacity'];

$food_ids = isset($_POST['food_id']) ? $_POST['food_id'] : array();
$coverage_ids = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : array();

$event_date = $_POST['event_date'];


// ===== 5 DAY VALIDATION ADDED =====

$today = date("Y-m-d");
$min_date = date("Y-m-d", strtotime("+5 days"));

if($event_date < $min_date)
{
    echo "<script>
    alert('Booking allowed only after 5 days from today.');
    window.history.back();
    </script>";
    exit();
}


// PRICE CALCULATION

$total = 0;


// venue price
$q=mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id");
$total += mysqli_fetch_assoc($q)['price'];


// decoration price
$q=mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id");
$total += mysqli_fetch_assoc($q)['price'];


// seat price
$q=mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id");
$seat_price = mysqli_fetch_assoc($q)['price'];
$total += $seat_price * $capacity;


// food price
foreach($food_ids as $fid)
{
$q=mysqli_query($conn,"SELECT price FROM food WHERE food_id=$fid");
$food_price = mysqli_fetch_assoc($q)['price'];
$total += $food_price * $capacity;
}


// coverage price
foreach($coverage_ids as $cid)
{
$q=mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$cid");
$total += mysqli_fetch_assoc($q)['price'];
}


// convert arrays to string
$food_string = implode(",", $food_ids);
$coverage_string = implode(",", $coverage_ids);


// PAYMENT SESSION STORE

$_SESSION['booking_data'] = array(

"user_id" => $user_id,
"event_id" => $event_id,
"package_id" => $package_id,
"event_date" => $event_date,
"total_price" => $total,
"food_ids" => $food_string,
"coverage_ids" => $coverage_string,
"capacity" => $capacity

);


// redirect to payment page
echo "<script>
window.location='payment.php';
</script>";

exit();

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Book Event</title>

<style>

/* NAVBAR */

.navbar{
background:#0f172a;
padding:15px 40px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logo{
color:#00c6ff;
font-size:22px;
font-weight:bold;
}

.menu a{
color:white;
text-decoration:none;
margin-left:20px;
font-size:15px;
}

.menu a:hover{
color:#00c6ff;
}


/* BODY */

body{
margin:0;
font-family:'Segoe UI', Arial;
background:url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;
min-height:100vh;
}


/* CONTAINER */

.container{
width:900px;
max-width:95%;
background:rgba(20,20,40,0.95);
padding:35px;
border-radius:18px;
color:white;
box-shadow:0 0 25px rgba(0,0,0,0.4);
margin:40px auto;
}

h3{
margin-bottom:20px;
font-size:26px;
}

label{
display:block;
margin-top:18px;
margin-bottom:6px;
}

select,input[type="number"],input[type="date"]{
width:100%;
padding:12px;
border-radius:10px;
border:1px solid rgba(255,255,255,0.2);
background:rgba(255,255,255,0.08);
color:white;
font-size:15px;
}

select option{
background:#1e293b;
color:white;
}


/* PACKAGE CARDS */

.package-title{
margin-bottom:10px;
font-size:18px;
}

.package-cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-bottom:25px;
}

.package-card{
background:rgba(255,255,255,0.08);
padding:20px;
border-radius:12px;
cursor:pointer;
transition:0.3s;
text-align:center;
border:2px solid transparent;
}

.package-card:hover{
background:rgba(0,198,255,0.25);
transform:translateY(-5px);
}

.package-card.active{
border:2px solid #00c6ff;
background:rgba(0,198,255,0.35);
}


/* CHECKBOX GROUP */

.checkbox-group{
background:rgba(255,255,255,0.08);
padding:15px;
border-radius:12px;
margin-top:8px;
display:grid;
grid-template-columns:repeat(2,1fr);
gap:10px;
}

.checkbox-group label{
display:flex;
align-items:center;
gap:10px;
margin:0;
cursor:pointer;
}

.checkbox-group input{
width:18px;
height:18px;
}


/* TOTAL */

#totalBox{
margin-top:20px;
font-size:20px;
text-align:center;
background:rgba(0,198,255,0.15);
padding:12px;
border-radius:10px;
}


/* BUTTON */

button{
width:100%;
padding:14px;
margin-top:15px;
background:#00c6ff;
border:none;
border-radius:12px;
cursor:pointer;
font-size:16px;
}

button:hover{
background:#0094cc;
}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">
EventHub
</div>

<div class="menu">

<a href="../index.php">Home</a>

<a href="my_bookings.php">My Bookings</a>

<a href="../logout.php">Logout</a>

</div>

</div>


<div class="container">

<h3>Book Event: <?php echo $event['event_name']; ?></h3>


<div class="package-title">Select Package</div>

<div class="package-cards">

<?php
mysqli_data_seek($packages,0);

while($row=mysqli_fetch_assoc($packages))
{

$active = ($package_id == $row['package_id']) ? "active" : "";

$icon="🥉";
if(strtolower($row['package_name'])=="standard") $icon="🥈";
if(strtolower($row['package_name'])=="premium") $icon="🥇";

echo "
<div class='package-card $active'
onclick='selectPackage(".$row['package_id'].")'>

<h3>$icon ".$row['package_name']."</h3>
<p>Click to select</p>

</div>
";
}
?>

</div>


<form id="packageForm" method="GET">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
<input type="hidden" name="package_id" id="packageInput">
</form>


<?php if($package_id!=""){ ?>

<form method="POST">

<input type="hidden" name="package_id" value="<?php echo $package_id; ?>">


<label>Capacity</label>
<input type="number" name="capacity" id="capacity" min="1" required>


<label>Select Venue</label>
<select name="venue_id" id="venueSelect" required>
<option value="">Select Venue</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM venues WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['venue_id']."' data-price='".$row['price']."'>
".$row['venue_name']." ₹".$row['price']."
</option>";
}
?>
</select>


<label>Select Decoration</label>
<select name="decoration_id" id="decorationSelect" required>
<option value="">Select Decoration</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM decorations WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['decoration_id']."' data-price='".$row['price']."'>
".$row['decoration_name']." ₹".$row['price']."
</option>";
}
?>
</select>


<label>Select Seat</label>
<select name="seat_id" id="seatSelect" required>
<option value="">Select Seat</option>

<?php
$q=mysqli_query($conn,"SELECT * FROM seats WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['seat_id']."' data-price='".$row['price']."'>
".$row['seat_type']." ₹".$row['price']." per person
</option>";
}
?>
</select>


<label>Select Food</label>
<div class="checkbox-group">

<?php
$q=mysqli_query($conn,"SELECT * FROM food WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<label>
<input type='checkbox'
name='food_id[]'
value='".$row['food_id']."'
data-price='".$row['price']."'
class='foodCheckbox'>

".$row['menu']." ₹".$row['price']." per person

</label>";
}
?>

</div>


<label>Select Coverage</label>
<div class="checkbox-group">

<?php
$q=mysqli_query($conn,"SELECT * FROM coverage WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<label>
<input type='checkbox'
name='coverage_id[]'
value='".$row['coverage_id']."'
data-price='".$row['price']."'
class='coverageCheckbox'>

".$row['coverage_type']." ₹".$row['price']."

</label>";
}
?>

</div>


<label>Select Date</label>

<input type="date"
name="event_date"
required
min="<?php echo date('Y-m-d', strtotime('+5 days')); ?>">


<div id="totalBox">
Total Price: ₹ <span id="totalAmount">0</span>
</div>


<button type="submit" name="book">
Book Now
</button>

</form>

<?php } ?>

</div>


<script>

function selectPackage(id)
{
document.getElementById("packageInput").value=id;
document.getElementById("packageForm").submit();
}

function calculateTotal(){

let total = 0;

let capacity = parseInt(document.getElementById("capacity")?.value) || 0;

let venue = document.getElementById("venueSelect");

if(venue?.value)
total += parseInt(venue.selectedOptions[0].dataset.price);

let decoration = document.getElementById("decorationSelect");

if(decoration?.value)
total += parseInt(decoration.selectedOptions[0].dataset.price);

let seat = document.getElementById("seatSelect");

if(seat?.value && capacity>0)
total += parseInt(seat.selectedOptions[0].dataset.price) * capacity;

document.querySelectorAll(".foodCheckbox:checked").forEach(cb=>{

if(capacity>0)
total += parseInt(cb.dataset.price) * capacity;

});

document.querySelectorAll(".coverageCheckbox:checked").forEach(cb=>{

total += parseInt(cb.dataset.price);

});

document.getElementById("totalAmount").innerText = total;

}

document.addEventListener("change", calculateTotal);

document.getElementById("capacity")?.addEventListener("input", calculateTotal);

</script>


</body>
</html>
