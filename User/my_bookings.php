<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

$query = "
SELECT 
b.*,
e.event_name,
p.package_name

FROM bookings b

JOIN events e ON b.event_id = e.event_id
JOIN packages p ON b.package_id = p.package_id

WHERE b.user_id = $user_id

ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>My Bookings</title>

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
background:linear-gradient(135deg,#1e3c72,#2a5298);
color:white;
}

.container{
max-width:1000px;
margin:30px auto;
padding:20px;
}


/* CARD */

.card{
background:rgba(255,255,255,0.08);
padding:20px;
margin-bottom:20px;
border-radius:15px;
}

.card-header{
display:flex;
justify-content:space-between;
margin-bottom:15px;
}

.event-name{
font-size:22px;
font-weight:bold;
}

.package{
background:#00c6ff;
padding:5px 12px;
border-radius:8px;
}


/* DETAILS GRID */

.details{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:10px;
}

.detail-box{
background:rgba(0,0,0,0.2);
padding:10px;
border-radius:8px;
}


/* STATUS */

.status{
padding:5px 10px;
border-radius:8px;
font-size:13px;
}

.upcoming{
background:#22c55e;
}

.completed{
background:#ef4444;
}

.payment-paid{
background:#22c55e;
}

.payment-pending{
background:#f59e0b;
}


/* BUTTON */

.btn{
margin-top:10px;
display:inline-block;
padding:8px 15px;
background:#00c6ff;
color:white;
text-decoration:none;
border-radius:8px;
}

.btn.disabled{
background:gray;
pointer-events:none;
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

<h2>My Bookings</h2>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

$event_date = $row['event_date'];

$change_last_date = date("Y-m-d", strtotime($event_date . " -5 days"));

$today = date("Y-m-d");

$is_edit_allowed = ($today <= $change_last_date);


// event status
$event_status = ($today <= $event_date)
? "<span class='status upcoming'>Upcoming</span>"
: "<span class='status completed'>Completed</span>";


// payment status
$payment_status = ($row['payment_status']=="Advance Paid")
? "<span class='status payment-pending'>Advance Paid</span>"
: "<span class='status payment-paid'>Fully Paid</span>";


echo "

<div class='card'>

<div class='card-header'>

<div class='event-name'>".$row['event_name']."</div>

<div class='package'>".$row['package_name']."</div>

</div>


<div class='details'>

<div class='detail-box'>
<b>Booking ID:</b><br>
".$row['booking_id']."
</div>

<div class='detail-box'>
<b>Capacity:</b><br>
".$row['capacity']."
</div>

<div class='detail-box'>
<b>Event Date:</b><br>
".$event_date."
</div>

<div class='detail-box'>
<b>Total Price:</b><br>
₹ ".$row['total_price']."
</div>

<div class='detail-box'>
<b>Advance Paid:</b><br>
₹ ".$row['advance_paid']."
</div>

<div class='detail-box'>
<b>Remaining Amount:</b><br>
₹ ".$row['remaining_amount']."
</div>

<div class='detail-box'>
<b>Payment Method:</b><br>
".$row['payment_method']."
</div>

<div class='detail-box'>
<b>Payment Status:</b><br>
".$payment_status."
</div>

<div class='detail-box'>
<b>Event Status:</b><br>
".$event_status."
</div>

<div class='detail-box'>
<b>Booked On:</b><br>
".$row['booking_date']."
</div>

</div>


<div style='margin-top:10px;color:#ffd166;'>
Changes allowed until: <b>".$change_last_date."</b>
</div>

";


if($is_edit_allowed)
{
echo "<a href='edit_booking.php?id=".$row['booking_id']."' class='btn'>Edit Booking</a>";
}
else
{
echo "<a class='btn disabled'>Edit Closed</a>";
}

echo "</div>";

}

}
else
{
echo "<div class='card'>No bookings found</div>";
}

?>

</div>

</body>
</html>
