<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id']))
{
    echo "Invalid Booking";
    exit();
}

$booking_id = intval($_GET['id']);


// FETCH BOOKING
$query = "
SELECT b.*, e.event_name
FROM bookings b
JOIN events e ON b.event_id = e.event_id
WHERE b.booking_id = $booking_id
AND b.user_id = $user_id
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result)==0)
{
    echo "Booking not found";
    exit();
}

$booking = mysqli_fetch_assoc($result);

$event_date = $booking['event_date'];


// CHECK EDIT DEADLINE
$last_edit_date = date("Y-m-d", strtotime($event_date . " -5 days"));
$today = date("Y-m-d");

if($today > $last_edit_date)
{
    echo "<h2>Edit not allowed. Deadline passed.</h2>";
    exit();
}



// UPDATE BOOKING
if(isset($_POST['update']))
{

$new_capacity = intval($_POST['capacity']);
$new_event_date = $_POST['event_date'];


// validate new date
$new_last_edit = date("Y-m-d", strtotime($new_event_date . " -5 days"));

if($today > $new_last_edit)
{
    echo "<script>alert('Cannot set this date. Less than 5 days remaining.');</script>";
}
else
{

// get seat price
$seat_q = mysqli_query($conn,"
SELECT s.price
FROM seats s
JOIN packages p ON s.package_id = p.package_id
WHERE p.package_id = ".$booking['package_id']."
LIMIT 1
");

$seat_price = mysqli_fetch_assoc($seat_q)['price'];

$new_total = $seat_price * $new_capacity;


// update query
mysqli_query($conn,"
UPDATE bookings
SET capacity='$new_capacity',
event_date='$new_event_date',
total_price='$new_total'
WHERE booking_id=$booking_id
");

echo "<script>
alert('Booking Updated Successfully');
window.location='my_bookings.php';
</script>";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Booking</title>

<style>

body{
font-family:Arial;
background:linear-gradient(135deg,#1e3c72,#2a5298);
color:white;
margin:0;
padding:0;
}

.container{
width:400px;
margin:80px auto;
background:rgba(255,255,255,0.1);
padding:25px;
border-radius:10px;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border:none;
border-radius:5px;
}

button{
width:100%;
padding:10px;
background:#00c6ff;
border:none;
color:white;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#0094cc;
}

.info{
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Booking</h2>

<div class="info">
<b>Event:</b> <?php echo $booking['event_name']; ?>
</div>

<div class="info">
<b>Edit allowed until:</b> <?php echo $last_edit_date; ?>
</div>

<form method="POST">

<label>Capacity</label>
<input type="number" name="capacity"
value="<?php echo $booking['capacity']; ?>"
required>

<label>Event Date</label>
<input type="date" name="event_date"
value="<?php echo $booking['event_date']; ?>"
required>

<button type="submit" name="update">
Update Booking
</button>

</form>

</div>

</body>
</html>
