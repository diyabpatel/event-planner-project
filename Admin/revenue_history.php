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

/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

/* BODY */
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* MAIN CONTENT */
.main-content{
margin-left:260px;
padding:30px;
}

/* HEADER */
.header{
background:white;
color:#5b21b6;
padding:20px 25px;
font-size:22px;
font-weight:600;
border-radius:16px;
box-shadow:0 10px 30px rgba(91,33,182,0.15);
margin-bottom:25px;
}

/* CONTAINER */
.container{
width:100%;
}

/* ================= CARDS ================= */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:22px;
border-radius:18px;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
text-align:center;
transition:0.3s;
border:1px solid #e9d5ff;
}

.card:hover{
transform:translateY(-5px);
box-shadow:0 20px 50px rgba(91,33,182,0.25);
}

.card h2{
margin:0;
font-size:26px;
color:#5b21b6;
}

.card p{
margin-top:6px;
font-size:13px;
color:#555;
}

/* ================= TABLE ================= */

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
}

/* HEADER */
th{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
padding:16px;
text-align:center;
font-weight:600;
}

/* DATA */
td{
padding:16px;
text-align:center;
border-bottom:1px solid #eee;
}

/* ROW HOVER */
tr:hover{
background:#f5f3ff;
}

/* STATUS */
.status-paid{
color:#16a34a;
font-weight:600;
}

.status-advance{
color:#f59e0b;
font-weight:600;
}

</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<div class="header">
Revenue History
</div>

<div class="container">

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
</div>
</body>
</html>