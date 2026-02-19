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


// ================= BOOK BUTTON =================

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


// convert arrays
$food_string = implode(",", $food_ids);
$coverage_string = implode(",", $coverage_ids);


// SAVE booking data in session for payment
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
}

.menu a:hover{
color:#00c6ff;
}


/* BODY */

body{
margin:0;
font-family:'Segoe UI';
background:url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;
min-height:100vh;
}


/* CONTAINER */

.container{
width:900px;
margin:40px auto;
background:rgba(20,20,40,0.95);
padding:30px;
border-radius:15px;
color:white;
}


/* FORM */

select,input{
width:100%;
padding:10px;
margin-top:5px;
margin-bottom:15px;
border-radius:8px;
border:none;
}

button{
width:100%;
padding:12px;
background:#00c6ff;
border:none;
color:white;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0094cc;
}


/* PACKAGE CARDS */

.package-cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:15px;
margin-bottom:20px;
}

.package-card{
background:rgba(255,255,255,0.1);
padding:15px;
cursor:pointer;
text-align:center;
border-radius:10px;
}

.package-card.active{
background:#00c6ff;
}

</style>

</head>

<body>


<div class="navbar">

<div class="logo">EventHub</div>

<div class="menu">

<a href="../index.php">Home</a>

<a href="my_bookings.php">My Bookings</a>

<a href="../logout.php">Logout</a>

</div>

</div>



<div class="container">

<h2>Book Event: <?php echo $event['event_name']; ?></h2>


<div class="package-cards">

<?php

mysqli_data_seek($packages,0);

while($row=mysqli_fetch_assoc($packages))
{

$active = ($package_id==$row['package_id'])?"active":"";

echo "<div class='package-card $active'
onclick='selectPackage(".$row['package_id'].")'>

<h3>".$row['package_name']."</h3>

</div>";

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
<input type="number" name="capacity" required>


<label>Venue</label>
<select name="venue_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM venues WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['venue_id']."'>
".$row['venue_name']." (₹".$row['price'].")
</option>";
}

?>

</select>


<label>Decoration</label>
<select name="decoration_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM decorations WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['decoration_id']."'>
".$row['decoration_name']." (₹".$row['price'].")
</option>";
}

?>

</select>


<label>Seat</label>
<select name="seat_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM seats WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['seat_id']."'>
".$row['seat_type']." (₹".$row['price']." per person)
</option>";
}

?>

</select>


<label>Event Date</label>
<input type="date" name="event_date" required>


<button type="submit" name="book">

Proceed to Payment

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

</script>


</body>
</html>
