<?php
session_start();
include("../db.php");

if(!isset($_SESSION['booking_data']))
{
echo "No booking data";
exit();
}

$data = $_SESSION['booking_data'];

$total = $data['total_price'];
$advance = round($total*0.25,2);
$remaining = $total-$advance;

if(isset($_POST['pay']))
{

$method = $_POST['payment_method'];

$receipt_no = "RCPT".date("Y").rand(1000,9999);

mysqli_query($conn,"
INSERT INTO bookings
(user_id,event_id,package_id,capacity,event_date,total_price,
advance_paid,remaining_amount,payment_status,payment_method,
food_ids,coverage_ids,receipt_no)

VALUES
(
'".$data['user_id']."',
'".$data['event_id']."',
'".$data['package_id']."',
'".$data['capacity']."',
'".$data['event_date']."',
'$total',
'$advance',
'$remaining',
'Advance Paid',
'$method',
'".$data['food_ids']."',
'".$data['coverage_ids']."',
'$receipt_no'
)
");

$booking_id=mysqli_insert_id($conn);

unset($_SESSION['booking_data']);

echo "<script>
alert('Booking Confirmed');
window.location='receipt.php?booking_id=$booking_id';
</script>";
exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Advance Payment</title>

<meta charset="UTF-8">

<style>

*{
box-sizing:border-box;
}

body{
margin:0;
font-family:Segoe UI,Arial;
background:linear-gradient(135deg,#0f172a,#1e293b);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
color:white;
}

.card{
width:430px;
background:#1e293b;
padding:30px;
border-radius:12px;
box-shadow:0 10px 30px rgba(0,0,0,0.5);
}

h2{
text-align:center;
margin-bottom:25px;
}

.section{
background:#020617;
padding:15px;
border-radius:8px;
margin-bottom:20px;
}

.row{
display:flex;
justify-content:space-between;
padding:8px 0;
border-bottom:1px solid #1e293b;
}

.row:last-child{
border-bottom:none;
}

.amount{
font-weight:600;
color:#38bdf8;
}

select,input{
width:100%;
padding:12px;
border-radius:8px;
border:none;
margin-top:12px;
font-size:14px;
background:#0f172a;
color:white;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:8px;
background:#3b82f6;
color:white;
font-size:15px;
font-weight:600;
margin-top:20px;
cursor:pointer;
transition:0.2s;
}

button:hover{
background:#2563eb;
}

.hidden{
display:none;
}

</style>

</head>

<body>

<div class="card">

<h2>Advance Payment</h2>

<div class="section">

<div class="row">
<span>Total Event Cost</span>
<span class="amount">₹ <?php echo number_format($total,2) ?></span>
</div>

<div class="row">
<span>Advance (25%)</span>
<span class="amount">₹ <?php echo number_format($advance,2) ?></span>
</div>

</div>

<form method="POST">

<select name="payment_method" id="method" required onchange="togglePayment()">
<option value="">Select Payment Method</option>
<option value="UPI">UPI</option>
<option value="Card">Card</option>
</select>

<input type="text" id="upiField" placeholder="Enter UPI ID (example@upi)" class="hidden">

<input type="text" id="cardField" placeholder="Enter 16 Digit Card Number" maxlength="16" class="hidden">

<button name="pay">
Pay Advance ₹ <?php echo number_format($advance,2) ?>
</button>

</form>

</div>

<script>

function togglePayment()
{

let method=document.getElementById("method").value;

let upi=document.getElementById("upiField");
let card=document.getElementById("cardField");

upi.classList.add("hidden");
card.classList.add("hidden");

upi.removeAttribute("required");
card.removeAttribute("required");

if(method=="UPI")
{
upi.classList.remove("hidden");
upi.setAttribute("required","required");
}

if(method=="Card")
{
card.classList.remove("hidden");
card.setAttribute("required","required");
}

}

document.querySelector("form").addEventListener("submit",function(e){

let method=document.getElementById("method").value;

if(method=="UPI")
{
let upi=document.getElementById("upiField").value;

let regex=/^[\w.-]+@[\w.-]+$/;

if(!regex.test(upi))
{
alert("Enter valid UPI ID");
e.preventDefault();
}
}

if(method=="Card")
{
let card=document.getElementById("cardField").value;

let regex=/^[0-9]{16}$/;

if(!regex.test(card))
{
alert("Enter valid 16 digit card number");
e.preventDefault();
}
}

});

</script>

</body>
</html>