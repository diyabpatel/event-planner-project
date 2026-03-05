<?php
session_start();
include("../db.php");

if(!isset($_SESSION['extra_payment']))
{
    echo "No edit data";
    exit();
}

$data = $_SESSION['extra_payment'];

$booking_id = $data['booking_id'];

$old_total = $data['previous_total'];
$old_advance = $data['previous_advance'];

$new_total = $data['new_total'];
$new_advance = $data['new_advance'];

$new_remaining = $new_total - $new_advance;

$difference = $new_advance - $old_advance;

$isRefund = false;

if($difference < 0)
{
    $isRefund = true;
    $difference = abs($difference);
}

if(isset($_POST['process']))
{

$method = $_POST['payment_method'];

/* ================= UPDATE BOOKING ================= */

$query = "
UPDATE bookings SET

capacity='".$data['capacity']."',
event_date='".$data['event_date']."',

total_price='".$data['new_total']."',
advance_paid='".$data['new_advance']."',
remaining_amount='".$new_remaining."',

food_ids='".$data['food_ids']."',
coverage_ids='".$data['coverage_ids']."',

payment_method='$method'

WHERE booking_id='".$booking_id."'
";

$result = mysqli_query($conn,$query);

if(!$result)
{
    echo mysqli_error($conn);
    exit();
}

unset($_SESSION['extra_payment']);

echo "<script>
alert('Booking Updated Successfully');
window.location='my_bookings.php';
</script>";

exit();

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Booking Payment</title>
<meta charset="UTF-8">

<style>

*{
box-sizing:border-box;
}

body{
margin:0;
font-family:Segoe UI, Arial;
background:linear-gradient(135deg,#0f172a,#1e293b);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
color:white;
}

.card{
width:460px;
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
margin-bottom:18px;
}

.section b{
display:block;
margin-bottom:10px;
color:#38bdf8;
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
}

.refund{
color:#22c55e;
}

.extra{
color:#f59e0b;
}

select,input{
width:100%;
padding:12px;
border-radius:8px;
border:none;
margin-top:10px;
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
margin-top:18px;
cursor:pointer;
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

<h2>Edit Booking Payment</h2>

<div class="section">

<b>Previous Payment</b>

<div class="row">
<span>Total</span>
<span class="amount">₹ <?php echo number_format($old_total,2); ?></span>
</div>

<div class="row">
<span>Advance Paid</span>
<span class="amount">₹ <?php echo number_format($old_advance,2); ?></span>
</div>

</div>

<div class="section">

<b>New Payment</b>

<div class="row">
<span>New Total</span>
<span class="amount">₹ <?php echo number_format($new_total,2); ?></span>
</div>

<div class="row">
<span>New Advance</span>
<span class="amount">₹ <?php echo number_format($new_advance,2); ?></span>
</div>

</div>

<div class="section">

<?php if($isRefund){ ?>

<b>Refund Amount</b>

<div class="row">
<span>Refund</span>
<span class="amount refund">₹ <?php echo number_format($difference,2); ?></span>
</div>

<?php } else { ?>

<b>Extra Payment Required</b>

<div class="row">
<span>Extra</span>
<span class="amount extra">₹ <?php echo number_format($difference,2); ?></span>
</div>

<?php } ?>

</div>

<form method="POST">

<select name="payment_method" id="method" required onchange="togglePayment()">
<option value="">Select Payment Method</option>
<option value="UPI">UPI</option>
<option value="Card">Card</option>
</select>

<input type="text" id="upiField" placeholder="Enter UPI ID (example@upi)" class="hidden">

<input type="text" id="cardField" placeholder="Enter 16 digit Card Number" maxlength="16" class="hidden">

<button name="process">

<?php if($isRefund){ ?>

Process Refund ₹ <?php echo number_format($difference,2); ?>

<?php } else { ?>

Pay Extra ₹ <?php echo number_format($difference,2); ?>

<?php } ?>

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