<?php
session_start();
include("../db.php");

/* ✅ FORCE UTF-8 */
header('Content-Type: text/html; charset=utf-8');

if(!isset($_SESSION['user_id'])){
    echo "Unauthorized";
    exit();
}

$booking_id = intval($_GET['booking_id']);

/* ✅ JOIN BOOKINGS + PAYMENTS */
$q = mysqli_query($conn,"
SELECT b.*, p.amount AS advance_paid, p.payment_method
FROM bookings b
LEFT JOIN payments p ON b.booking_id = p.booking_id
WHERE b.booking_id='$booking_id'
AND b.user_id='".$_SESSION['user_id']."'
");

$data = mysqli_fetch_assoc($q);

/* ✅ HANDLE NULL VALUES SAFELY */
$advance_paid = isset($data['advance_paid']) ? $data['advance_paid'] : 0;

/* ✅ CALCULATE REMAINING */
$remaining = $data['total_price'] - $advance_paid;
?>

<!DOCTYPE html>
<html>
<head>

<title>Payment Receipt</title>
<meta charset="UTF-8">

<style>

*{box-sizing:border-box;}

body{
font-family:'Segoe UI', Arial;
background:linear-gradient(135deg,#ffffff,#f6f4ff);
padding:40px;
margin:0;
color:#1e1b4b;
}

/* BACK BUTTON */

.back-btn{
position:fixed;
top:20px;
left:20px;
background:linear-gradient(135deg,#7c3aed,#a78bfa);
color:white;
padding:10px 18px;
border-radius:25px;
text-decoration:none;
font-size:14px;
font-weight:500;
box-shadow:0 8px 20px rgba(124,58,237,0.3);
transition:0.3s;
z-index:9999;
}

.back-btn:hover{
transform:translateY(-2px) scale(1.05);
box-shadow:0 12px 25px rgba(124,58,237,0.4);
}

/* RECEIPT CARD */

.receipt{
max-width:600px;
margin:auto;
background:#ffffff;
padding:35px;
border-radius:16px;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
border:1px solid #eee;
}

/* HEADER */

.header{
text-align:center;
margin-bottom:25px;
}

.header h2{
margin:0;
font-size:26px;
background:linear-gradient(90deg,#7c3aed,#a78bfa);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.header p{
color:#6d6aa3;
margin-top:5px;
}

/* SECTION */

.section{margin-top:15px;}

.row{
display:flex;
justify-content:space-between;
padding:12px 0;
border-bottom:1px solid #eee;
}

.row:last-child{border-bottom:none;}

.label{
font-weight:600;
color:#6d6aa3;
}

.value{
font-weight:500;
color:#1e1b4b;
}

.amount{
font-weight:700;
color:#7c3aed;
}

/* TOTAL BOX */

.total-box{
background:#f6f4ff;
padding:15px;
border-radius:10px;
margin-top:15px;
}

/* BUTTONS */

.buttons{
margin-top:25px;
display:flex;
gap:10px;
}

button{
flex:1;
padding:12px;
border:none;
font-size:15px;
border-radius:25px;
cursor:pointer;
transition:0.3s;
}

/* PRINT */

.print-btn{
background:linear-gradient(135deg,#7c3aed,#a78bfa);
color:white;
}

.print-btn:hover{
transform:scale(1.05);
}

/* BOOKINGS */

.bookings-btn{
background:linear-gradient(135deg,#7c3aed,#a78bfa)
color:white;
}

.bookings-btn:hover{
transform:scale(1.05);
}

/* PRINT MODE */

@media print{
body{background:white;padding:0;}
button{display:none;}
.back-btn{display:none;}
.receipt{box-shadow:none;}
}

</style>

</head>

<body>

<div class="receipt">

<a href="/event-planner-project/index.php" class="back-btn">← Back</a>

<div class="header">
<h2>Event Booking Receipt</h2>
<p>Thank you for your booking</p>
</div>

<div class="section">

<div class="row">
<span class="label">Booking ID</span>
<span class="value">#<?php echo htmlspecialchars($data['booking_id']); ?></span>
</div>

<div class="row">
<span class="label">Event Date</span>
<span class="value"><?php echo htmlspecialchars($data['event_date']); ?></span>
</div>

</div>

<div class="total-box">

<div class="row">
<span class="label">Total Amount</span>
<span class="amount">&#8377; <?php echo number_format($data['total_price'],2); ?></span>
</div>

<div class="row">
<span class="label">Advance Paid</span>
<span class="amount">&#8377; <?php echo number_format($advance_paid,2); ?></span>
</div>

<div class="row">
<span class="label">Remaining Amount</span>
<span class="amount">&#8377; <?php echo number_format($remaining,2); ?></span>
</div>

<div class="row">
<span class="label">Payment Method</span>
<span class="value"><?php echo htmlspecialchars($data['payment_method']); ?></span>
</div>

</div>

<div class="buttons">

<button class="print-btn" onclick="window.print()">
Print / Download
</button>

<button class="print-btn" onclick="window.location='my_bookings.php'">
Go to My Bookings
</button>

</div>

</div>

</body>
</html>