<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit();
}

/* TOTAL REVENUE RECEIVED */

$total_revenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
SUM(CASE WHEN payment_status='Full Payment Completed' THEN total_price ELSE 0 END) +
SUM(CASE WHEN payment_status='Advance Paid' THEN advance_paid ELSE 0 END)
AS total
FROM bookings
"));

/* TOTAL ADVANCE COLLECTED */

$total_advance = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(advance_paid) AS total
FROM bookings
WHERE payment_status='Advance Paid'
"));

/* TOTAL REMAINING */

$total_remaining = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(remaining_amount) AS total
FROM bookings
WHERE payment_status='Advance Paid'
"));

/* TOTAL BOOKINGS */

$total_bookings = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total FROM bookings
"));

/* BOOKING LIST */

$query = mysqli_query($conn,"
SELECT 
b.*,
u.college_name,
e.event_name
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN events e ON b.event_id = e.event_id
ORDER BY b.booking_date DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Revenue History</title>

<style>

body{
font-family:Segoe UI;
background:#f1f5f9;
margin:0;
}

.header{
background:#1e40af;
color:white;
padding:18px 30px;
font-size:22px;
font-weight:600;
}

.container{
padding:30px;
max-width:1200px;
margin:auto;
}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
text-align:center;
}

.card h2{
margin:0;
font-size:28px;
color:#1e40af;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

th,td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#2563eb;
color:white;
}

tr:hover{
background:#f1f5f9;
}

.status-paid{
color:green;
font-weight:bold;
}

.status-advance{
color:orange;
font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
Revenue History
</div>

<div class="container">

<div class="cards">

<div class="card">
<h2>&#8377; <?php echo number_format($total_revenue['total'] ?: 0,2); ?></h2>
<p>Total Revenue Received</p>
</div>

<div class="card">
<h2>&#8377; <?php echo number_format($total_advance['total'] ?: 0,2); ?></h2>
<p>Total Advance Collected</p>
</div>

<div class="card">
<h2>&#8377; <?php echo number_format($total_remaining['total'] ?: 0,2); ?></h2>
<p>Total Remaining Payments</p>
</div>

<div class="card">
<h2><?php echo $total_bookings['total']; ?></h2>
<p>Total Bookings</p>
</div>

</div>

<table>

<tr>
<th>Booking ID</th>
<th>College</th>
<th>Event</th>
<th>Total Price</th>
<th>Advance Paid</th>
<th>Remaining</th>
<th>Status</th>
<th>Booking Date</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($query)){

if($row['payment_status']=="Full Payment Done"){
$status="<span class='status-paid'>Full Payment Done</span>";
}else{
$status="<span class='status-advance'>Advance Paid</span>";
}

echo "<tr>
<td>".$row['booking_id']."</td>
<td>".$row['college_name']."</td>
<td>".$row['event_name']."</td>
<td>&#8377; ".number_format($row['total_price'],2)."</td>
<td>&#8377; ".number_format($row['advance_paid'],2)."</td>
<td>&#8377; ".number_format($row['remaining_amount'],2)."</td>
<td>$status</td>
<td>".$row['booking_date']."</td>
</tr>";
}
?>

</table>

</div>

</body>
</html>