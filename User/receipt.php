<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    echo "Unauthorized";
    exit();
}

$q = mysqli_query($conn,"
SELECT * FROM bookings 
WHERE user_id='".$_SESSION['user_id']."' 
ORDER BY booking_id DESC LIMIT 1
");

$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Receipt</title>
<style>
body{
font-family:Segoe UI;
background:#f4f6f8;
padding:40px;
}
.receipt{
max-width:600px;
margin:auto;
background:#fff;
padding:30px;
border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,0.15);
}
h2{text-align:center;margin-bottom:20px;}
.row{
display:flex;
justify-content:space-between;
margin:10px 0;
}
hr{border:1px dashed #ccc;}
button{
margin-top:20px;
width:100%;
padding:12px;
background:#007bff;
border:none;
color:white;
font-size:16px;
border-radius:8px;
cursor:pointer;
}
</style>
</head>

<body>

<div class="receipt">
<h2>Event Booking Receipt</h2>

<div class="row"><b>Booking ID</b><span>#<?php echo $data['booking_id']; ?></span></div>
<div class="row"><b>Event Date</b><span><?php echo $data['event_date']; ?></span></div>
<hr>
<div class="row"><b>Total Amount</b><span>Rs. <?php echo $data['total_price']; ?></span></div>
<div class="row"><b>Advance Paid</b><span>Rs. <?php echo $data['advance_paid']; ?></span></div>
<div class="row"><b>Remaining</b><span>Rs. <?php echo $data['remaining_amount']; ?></span></div>
<div class="row"><b>Payment Method</b><span><?php echo $data['payment_method']; ?></span></div>
<div class="row"><b>Status</b><span><?php echo $data['payment_status']; ?></span></div>

<button onclick="window.print()">Print / Download</button>
</div>

</body>
</html>