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
font-family:Segoe UI,Arial;
background:linear-gradient(135deg,#eef2f7,#dbeafe);
padding:40px;
margin:0;
}

.receipt{
max-width:600px;
margin:auto;
background:#fff;
padding:35px;
border-radius:12px;
box-shadow:0 12px 35px rgba(0,0,0,0.15);
}

.header{text-align:center;margin-bottom:25px;}

.header h2{margin:0;color:#1e293b;}

.header p{color:#64748b;margin-top:5px;}

.section{margin-top:15px;}

.row{
display:flex;
justify-content:space-between;
padding:10px 0;
border-bottom:1px solid #e5e7eb;
}

.row:last-child{border-bottom:none;}

.label{font-weight:600;color:#334155;}

.value{font-weight:500;color:#0f172a;}

.amount{font-weight:700;color:#2563eb;}

.total-box{
background:#f1f5f9;
padding:15px;
border-radius:8px;
margin-top:15px;
}

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
border-radius:8px;
cursor:pointer;
}

.print-btn{
background:#2563eb;
color:white;
}

.bookings-btn{
background:#16a34a;
color:white;
}

@media print{
body{background:white;padding:0;}
button{display:none;}
.receipt{box-shadow:none;}
}

</style>

</head>

<body>

<div class="receipt">

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

<button class="bookings-btn" onclick="window.location='my_bookings.php'">
Go to My Bookings
</button>

</div>

</div>

</body>
</html>