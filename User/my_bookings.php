<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

/* UTF-8 */
header('Content-Type: text/html; charset=utf-8');

$user_id = $_SESSION['user_id'];

/* ✅ FILTER */
$filter = isset($_GET['status']) ? $_GET['status'] : 'approved';

/* ✅ CORRECT STATUS CONDITION (PAYMENT BASED) */
if($filter == 'pending'){
    $status_condition = "AND b.booking_id IN (
        SELECT booking_id FROM payments 
        WHERE payment_status = 'Verification Pending'
    )";
}else{
    $status_condition = "AND b.booking_id IN (
        SELECT booking_id FROM payments 
        WHERE payment_status = 'Confirmed'
    )";
}

/* QUERY */
$query = "
SELECT 
b.*,
e.event_name,
p.package_name
FROM bookings b
LEFT JOIN events e ON b.event_id = e.event_id
LEFT JOIN packages p ON b.package_id = p.package_id
WHERE b.user_id = $user_id
$status_condition
ORDER BY b.booking_date DESC
";

/* ✅ DEBUG SAFE */
$result = mysqli_query($conn,$query);
if(!$result){
    die("SQL Error: " . mysqli_error($conn));
}
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

.container{
    max-width:1100px;
    margin:50px auto;
    padding:10px;
}

h2{
    text-align:center;
    margin-bottom:20px;
    font-size:30px;
    background:linear-gradient(90deg,#7c3aed,#a78bfa);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* FILTER BUTTON */
.filter-btn{
    padding:10px 25px;
    margin:5px;
    border-radius:30px;
    text-decoration:none;
    font-size:14px;
    background:#e5e7eb;
    color:#4b5563;
}

.filter-btn.active{
    background:linear-gradient(135deg,#7c3aed,#a78bfa);
    color:white;
}

/* CARD */
.card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    margin-bottom:30px;
    box-shadow:0 10px 30px rgba(124,58,237,0.15);
}

.card-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.event-name{
    font-size:22px;
    color:#7c3aed;
}

.package{
    background:#7c3aed;
    padding:6px 16px;
    border-radius:30px;
    font-size:12px;
    color:white;
}

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

.status{
    padding:5px 12px;
    border-radius:20px;
    font-size:11px;
    color:white;
}

.upcoming{background:#7c3aed;}
.completed{background:#ef4444;}
.payment-paid{background:#16a34a;}
.payment-pending{background:#f59e0b;}

.bottom{
    margin-top:20px;
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
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
    background:#7c3aed;
}

.btn.disabled{
    background:#9ca3af;
    pointer-events:none;
}

.btn.receipt{background:#16a34a;}
.btn.feedback{background:#f59e0b;}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>My Bookings</h2>

<!-- FILTER -->
<div style="text-align:center; margin-bottom:30px;">
<a href="?status=approved" class="filter-btn <?php echo ($filter=='approved')?'active':''; ?>">Approved</a>
<a href="?status=pending" class="filter-btn <?php echo ($filter=='pending')?'active':''; ?>">Pending</a>
</div>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

$booking_id = $row['booking_id'];

/* PAYMENT */
$paidData = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total 
FROM payments 
WHERE booking_id = $booking_id
"));

$totalPaid = (isset($paidData['total'])) ? $paidData['total'] : 0;
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

$event_name = isset($row['event_name']) ? $row['event_name'] : "Event Deleted";
$package_name = isset($row['package_name']) ? $row['package_name'] : "Package Deleted";

echo "

<div class='card'>

<div class='card-header'>
<div class='event-name'>$event_name</div>
<div class='package'>$package_name</div>
</div>

<div class='details'>

<div class='detail-box'><b>Booking ID</b><br>$booking_id</div>
<div class='detail-box'><b>Capacity</b><br>{$row['capacity']}</div>
<div class='detail-box'><b>Event Date</b><br>$event_date</div>

<div class='detail-box'><b>Total Price</b><br>₹ ".number_format($row['total_price'],2)."</div>
<div class='detail-box'><b>Paid</b><br>₹ ".number_format($totalPaid,2)."</div>
<div class='detail-box'><b>Remaining</b><br>₹ ".number_format($remaining,2)."</div>

<div class='detail-box'><b>Payment</b><br>$payment_status</div>
<div class='detail-box'><b>Status</b><br>$event_status</div>
<div class='detail-box'><b>Booked On</b><br>{$row['booking_date']}</div>

</div>

<div class='bottom'>

<div>Changes allowed until: <b>$change_last_date</b></div>

<div class='actions'>
";

if($is_edit_allowed){
    echo "<a href='edit_booking.php?id=$booking_id' class='btn'>Edit</a>";
}else{
    echo "<a class='btn disabled'>Edit Closed</a>";
}

echo "<a href='receipt.php?booking_id=$booking_id' class='btn receipt'>Receipt</a>";

if($today > $event_date){

$check=mysqli_query($conn,"
SELECT * FROM feedback 
WHERE booking_id='$booking_id' 
AND user_id='$user_id'
");

if(mysqli_num_rows($check)==0){
    echo "<a href='feedback.php?booking_id=$booking_id' class='btn feedback'>Feedback</a>";
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