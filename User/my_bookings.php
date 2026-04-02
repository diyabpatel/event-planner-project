<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

/* ✅ FORCE UTF-8 */
header('Content-Type: text/html; charset=utf-8');

$user_id = $_SESSION['user_id'];

/* ✅ FIXED QUERY (LEFT JOIN + NO FILTER) */
$query = "
SELECT 
b.*,
e.event_name,
p.package_name
FROM bookings b
LEFT JOIN events e ON b.event_id = e.event_id
LEFT JOIN packages p ON b.package_id = p.package_id
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

body{
    margin:0;
    font-family:'Segoe UI', system-ui;
    background:linear-gradient(135deg,#ffffff,#f6f4ff);
    color:#1e1b4b;
}

/* container */
.container{
    max-width:1100px;
    margin:50px auto;
    padding:10px;
}

/* heading */
h2{
    text-align:center;
    margin-bottom:40px;
    font-size:30px;
    background:linear-gradient(90deg,#7c3aed,#a78bfa);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* card */
.card{
    background:#ffffff;
    border-radius:18px;
    padding:25px;
    margin-bottom:30px;
    box-shadow:0 10px 30px rgba(124,58,237,0.15);
    transition:0.3s;
    border:1px solid #eee;
}

.card:hover{
    transform:translateY(-5px);
}

/* header */
.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.event-name{
    font-size:22px;
    font-weight:600;
    color:#7c3aed;
}

.package{
    background:linear-gradient(135deg,#7c3aed,#a78bfa);
    padding:6px 16px;
    border-radius:30px;
    font-size:12px;
    color:white;
}

/* grid */
.details{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.detail-box{
    background:#f6f4ff;
    padding:14px;
    border-radius:12px;
    font-size:13px;
}

.detail-box b{
    color:#7c3aed;
    font-size:12px;
}

/* status */
.status{
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    margin-top:5px;
    color:white;
}

.upcoming{background:#7c3aed;}
.completed{background:#ef4444;}
.payment-paid{background:#16a34a;}
.payment-pending{background:#f59e0b;}

/* bottom */
.bottom{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
}

.change-date{
    font-size:13px;
    color:#7c3aed;
}

/* buttons */
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
    background:linear-gradient(135deg,#7c3aed,#a78bfa);
    transition:0.3s;
}

.btn:hover{
    transform:scale(1.05);
}

.btn.disabled{
    background:#9ca3af;
    pointer-events:none;
}

.btn.receipt{
    background:linear-gradient(135deg,#16a34a,#4ade80);
}

.btn.feedback{
    background:linear-gradient(135deg,#f59e0b,#facc15);
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

/* ✅ SAFE PAYMENT CALCULATION */
$paidData = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total 
FROM payments 
WHERE booking_id = $booking_id
"));

$totalPaid = ($paidData && $paidData['total']) ? $paidData['total'] : 0;
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

/* ✅ HANDLE NULL EVENT / PACKAGE */
$event_name = $row['event_name'] ? htmlspecialchars($row['event_name']) : "Event Deleted";
$package_name = $row['package_name'] ? htmlspecialchars($row['package_name']) : "Package Deleted";

echo "

<div class='card'>

<div class='card-header'>
<div class='event-name'>".$event_name."</div>
<div class='package'>".$package_name."</div>
</div>

<div class='details'>

<div class='detail-box'><b>Booking ID</b><br>".htmlspecialchars($booking_id)."</div>
<div class='detail-box'><b>Capacity</b><br>".htmlspecialchars($row['capacity'])."</div>
<div class='detail-box'><b>Event Date</b><br>".htmlspecialchars($event_date)."</div>

<div class='detail-box'><b>Total Price</b><br>&#8377; ".number_format($row['total_price'],2)."</div>
<div class='detail-box'><b>Paid</b><br>&#8377; ".number_format($totalPaid,2)."</div>
<div class='detail-box'><b>Remaining</b><br>&#8377; ".number_format($remaining,2)."</div>

<div class='detail-box'><b>Payment</b><br>".$payment_status."</div>
<div class='detail-box'><b>Status</b><br>".$event_status."</div>
<div class='detail-box'><b>Booked On</b><br>".htmlspecialchars($row['booking_date'])."</div>

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
    echo "<div class='card'>No bookings found</div>";
}

?>

</div>

</body>
</html>