<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

date_default_timezone_set('Asia/Kolkata');

$user_id = $_SESSION['user_id'];

/* FILTER */
$filter = isset($_GET['status']) ? $_GET['status'] : 'approved';

/* FILTER LOGIC */
if($filter == 'pending'){
    $status_condition = "AND b.payment_status = 'Pending'";
}else{
    $status_condition = "AND (b.payment_status = 'Approved' OR b.payment_status = 'Full Payment Done')";
}

/* ✅ IMPORTANT: ADD is_completed FROM SQL */
$query = "
SELECT b.*, e.event_name, e.image, p.package_name,
CASE 
    WHEN b.event_date < CURDATE() THEN 1 
    ELSE 0 
END AS is_completed
FROM bookings b
LEFT JOIN events e ON b.event_id = e.event_id
LEFT JOIN packages p ON b.package_id = p.package_id
WHERE b.user_id = $user_id
$status_condition
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
body{margin:0;font-family:'Segoe UI', system-ui;background:#f4f5f9;}
.container{max-width:1100px;margin:50px auto;padding:10px;}
h2{text-align:center;margin-bottom:30px;}
.filter-btn{padding:8px 18px;margin:5px;border-radius:20px;text-decoration:none;background:#e5e7eb;color:#333;}
.filter-btn.active{background:#7c3aed;color:white;}
.card{display:flex;background:#fff;border-radius:14px;margin-bottom:25px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.08);}
.card-img{width:220px;height:180px;}
.card-img img{width:100%;height:100%;object-fit:cover;}
.card-content{flex:1;padding:18px;}
.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;}
.event-name{font-size:18px;font-weight:600;}
.package{font-size:12px;color:#7c3aed;margin-top:3px;}
.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;}
.info-grid span{font-size:12px;color:#6b7280;}
.info-grid b{display:block;font-size:14px;margin-top:3px;}
.status{padding:4px 10px;border-radius:20px;font-size:11px;color:white;}
.payment-paid{background:#16a34a;}
.payment-pending{background:#f59e0b;}
.edit-window{margin-top:10px;font-size:12px;color:#6b7280;}
.actions{margin-top:15px;display:flex;gap:10px;}
.btn{padding:6px 14px;border-radius:20px;text-decoration:none;color:white;background:#7c3aed;font-size:12px;}
.btn.disabled{background:#9ca3af;pointer-events:none;}
.btn.receipt{background:#16a34a;}
.btn.feedback{background:#f59e0b;}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>My Bookings</h2>

<div style="text-align:center;">
<a href="?status=approved" class="filter-btn <?php echo ($filter=='approved')?'active':''; ?>">Approved</a>
<a href="?status=pending" class="filter-btn <?php echo ($filter=='pending')?'active':''; ?>">Pending</a>
</div>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

$booking_id = $row['booking_id'];
$is_completed = $row['is_completed']; // ✅ FROM SQL

/* PAYMENT */
$paidData = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total 
FROM payments 
WHERE booking_id = $booking_id
"));

$totalPaid = isset($paidData['total']) ? $paidData['total'] : 0;
$remaining = $row['total_price'] - $totalPaid;

/* STATUS */
$status = $row['payment_status'];

if($status == 'Full Payment Done'){
    $payment_status = "<span class='status payment-paid'>Full Payment Done</span>";
}
else if($status == 'Approved'){
    $payment_status = "<span class='status payment-paid'>Approved</span>";
}
else{
    $payment_status = "<span class='status payment-pending'>Pending</span>";
}

$event_name = isset($row['event_name']) ? $row['event_name'] : "Event Deleted";
$image = isset($row['image']) ? $row['image'] : "default.jpg";
$package_name = isset($row['package_name']) ? $row['package_name'] : "Package Deleted";

?>

<div class="card">

<div class="card-img">
<img src="../uploads/images/events_images/<?php echo $image; ?>">
</div>

<div class="card-content">

<div class="top">
<div>
<div class="event-name"><?php echo $event_name; ?></div>
<div class="package"><?php echo $package_name; ?></div>
</div>
<div><?php echo $payment_status; ?></div>
</div>

<div class="info-grid">
<div><span>Capacity</span><b><?php echo $row['capacity']; ?></b></div>
<div><span>Event Date</span><b><?php echo $row['event_date']; ?></b></div>
<div><span>Total Price</span><b>₹ <?php echo number_format($row['total_price'],2); ?></b></div>
<div><span>Advance Paid</span><b>₹ <?php echo number_format($totalPaid,2); ?></b></div>
<div><span>Remaining</span><b>₹ <?php echo number_format($remaining,2); ?></b></div>
</div>

<div class="actions">

<a href="receipt.php?booking_id=<?php echo $booking_id; ?>" class="btn receipt">Receipt</a>

<?php
/* ✅ FINAL FEEDBACK (NOW 100% WORKS) */
if($is_completed){

$check=mysqli_query($conn,"
SELECT * FROM feedback 
WHERE booking_id='$booking_id' 
AND user_id='$user_id'
");

if(mysqli_num_rows($check)==0){
echo "<a href='feedback.php?booking_id=$booking_id' class='btn feedback'>Give Feedback</a>";
}else{
echo "<a class='btn disabled'>Feedback Submitted</a>";
}

}
?>

</div>

</div>

</div>

<?php
}

}else{
echo "<div class='card'>No bookings found</div>";
}
?>

</div>

</body>
</html>