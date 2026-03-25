<?php
session_start();

if(!isset($_SESSION['user_id'])){
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
AND b.payment_status = 'Advance Paid'
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

body{
    margin:0;
    font-family:'Segoe UI', system-ui;
    background:linear-gradient(135deg,#0b0f1a,#121a2e,#1a2742);
    color:#eaeaff;
}

.container{
    max-width:1100px;
    margin:50px auto;
    padding:20px;
}

h2{
    text-align:center;
    margin-bottom:40px;
    font-size:30px;
    background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.card{
    background:rgba(255,255,255,0.05);
    border-radius:18px;
    padding:25px;
    margin-bottom:30px;
    box-shadow:0 20px 50px rgba(0,0,0,0.5);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.event-name{
    font-size:22px;
    font-weight:600;
}

.package{
    background:#4f7cff;
    padding:6px 16px;
    border-radius:30px;
    font-size:12px;
}

.details{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.detail-box{
    background:#0b1220;
    padding:14px;
    border-radius:12px;
    font-size:13px;
}

.detail-box b{
    color:#9bb6ff;
    font-size:12px;
}

.status{
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    margin-top:5px;
}

.upcoming{background:#16a34a;}
.completed{background:#ef4444;}
.payment-paid{background:#16a34a;}
.payment-pending{background:#f59e0b;}

.bottom{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.change-date{
    font-size:13px;
    color:#facc15;
}

.actions{
    display:flex;
    gap:10px;
}

.btn{
    padding:8px 18px;
    border-radius:25px;
    text-decoration:none;
    color:white;
    font-size:13px;
    background:#4f7cff;
}

.btn.disabled{
    background:#4b5563;
    pointer-events:none;
}

.btn.receipt{
    background:#16a34a;
}

.btn.feedback{
    background:#f59e0b;
}

</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>My Bookings</h2>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

$booking_id = $row['booking_id'];

/* PAYMENT CALCULATION */
$paidData = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total 
FROM payments 
WHERE booking_id = $booking_id
"));

$totalPaid = isset($paidData['total']) ? $paidData['total'] : 0;
$remaining = $row['total_price'] - $totalPaid;

/* STATUS */
$event_date = $row['event_date'];
$today = date("Y-m-d");

$event_status = ($today <= $event_date)
? "<span class='status upcoming'>Upcoming</span>"
: "<span class='status completed'>Completed</span>";

$payment_status = ($remaining <= 0)
? "<span class='status payment-paid'>Fully Paid</span>"
: "<span class='status payment-pending'>Advance Paid</span>";

$change_last_date = date("Y-m-d", strtotime($event_date . " -2 days"));
$is_edit_allowed = ($today <= $change_last_date);

echo "

<div class='card'>

<div class='card-header'>
<div class='event-name'>".$row['event_name']."</div>
<div class='package'>".$row['package_name']."</div>
</div>

<div class='details'>

<div class='detail-box'><b>Booking ID</b><br>".$booking_id."</div>
<div class='detail-box'><b>Capacity</b><br>".$row['capacity']."</div>
<div class='detail-box'><b>Event Date</b><br>".$event_date."</div>

<div class='detail-box'><b>Total Price</b><br>₹ ".$row['total_price']."</div>
<div class='detail-box'><b>Paid</b><br>₹ ".$totalPaid."</div>
<div class='detail-box'><b>Remaining</b><br>₹ ".$remaining."</div>

<div class='detail-box'><b>Payment</b><br>".$payment_status."</div>
<div class='detail-box'><b>Status</b><br>".$event_status."</div>
<div class='detail-box'><b>Booked On</b><br>".$row['booking_date']."</div>

</div>

<div class='bottom'>

<div class='change-date'>
Changes allowed until: <b>".$change_last_date."</b>
</div>

<div class='actions'>
";

if($is_edit_allowed){
    echo "<a href='edit_booking.php?id=".$booking_id."' class='btn'>Edit</a>";
}else{
    echo "<a class='btn disabled'>Edit Closed</a>";
}

echo "<a href='receipt.php?booking_id=".$booking_id."' class='btn receipt'>Receipt</a>";

if($today > $event_date){

$check=mysqli_query($conn,"
SELECT * FROM feedback 
WHERE booking_id='$booking_id' 
AND user_id='$user_id'
");

if(mysqli_num_rows($check)==0){
    echo "<a href='feedback.php?booking_id=".$booking_id."' class='btn feedback'>Feedback</a>";
}else{
    echo "<a class='btn disabled'>Feedback Done</a>";
}

}

echo "
</div>
</div>
</div>
";

}

}else{
    echo "<div class='card'>No confirmed bookings found</div>";
}

?>

</div>

</body>
</html>