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


// fetch event
$event_query = "SELECT * FROM events WHERE event_id=$event_id";
$event_result = mysqli_query($conn,$event_query);
$event = mysqli_fetch_assoc($event_result);


// fetch packages
$package_query = "SELECT * FROM packages WHERE event_id=$event_id";
$packages = mysqli_query($conn,$package_query);


// form submit
if(isset($_POST['book']))
{

$package_id = $_POST['package_id'];
$venue_id = $_POST['venue_id'];
$decoration_id = $_POST['decoration_id'];
$seat_id = $_POST['seat_id'];
$food_id = $_POST['food_id'];
$coverage_id = $_POST['coverage_id'];
$event_date = $_POST['event_date'];


// calculate total

$total = 0;

$q = mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM food WHERE food_id=$food_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$coverage_id");
$total += mysqli_fetch_assoc($q)['price'];


// insert booking

$query = "INSERT INTO bookings
(user_id,event_id,package_id,event_date,total_price)
VALUES
('$user_id','$event_id','$package_id','$event_date','$total')";

mysqli_query($conn,$query);

echo "<script>alert('Booking Successful');</script>";

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Book Event</title>

<style>

body{
font-family:Arial;
background:#f4f6f8;
}

.container{
width:600px;
margin:auto;
background:white;
padding:30px;
margin-top:40px;
box-shadow:0px 0px 10px gray;
}

select,input{
width:100%;
padding:10px;
margin:10px 0;
}

button{
background:#3498db;
color:white;
padding:10px;
border:none;
cursor:pointer;
width:100%;
}

h2{
text-align:center;
}

</style>

</head>

<body>

<div class="container">

<h2>Book Event: <?php echo $event['event_name']; ?></h2>

<form method="POST">


<label>Select Package</label>

<select name="package_id" required onchange="this.form.submit()">

<option value="">Select Package</option>

<?php

while($row=mysqli_fetch_assoc($packages))
{
$selected = (isset($_POST['package_id']) && $_POST['package_id']==$row['package_id']) ? "selected" : "";
echo "<option value='".$row['package_id']."' $selected>".$row['package_name']."</option>";
}

?>

</select>


<?php

if(isset($_POST['package_id']))
{

$package_id = $_POST['package_id'];

?>


<label>Select Venue</label>

<select name="venue_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM venues WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['venue_id']."'>".$row['venue_name']." (₹".$row['price'].")</option>";
}

?>

</select>



<label>Select Decoration</label>

<select name="decoration_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM decorations WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['decoration_id']."'>".$row['decoration_name']." (₹".$row['price'].")</option>";
}

?>

</select>



<label>Select Seat Type</label>

<select name="seat_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM seats WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['seat_id']."'>".$row['seat_type']." (₹".$row['price'].")</option>";
}

?>

</select>



<label>Select Food</label>

<select name="food_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM food WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['food_id']."'>".$row['menu']." (₹".$row['price'].")</option>";
}

?>

</select>



<label>Select Coverage</label>

<select name="coverage_id" required>

<?php

$q=mysqli_query($conn,"SELECT * FROM coverage WHERE package_id=$package_id");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['coverage_id']."'>".$row['coverage_type']." (₹".$row['price'].")</option>";
}

?>

</select>



<label>Select Event Date</label>

<input type="date" name="event_date" required>


<button type="submit" name="book">

Confirm Booking

</button>


<?php
}
?>


</form>

</div>

</body>
</html>
