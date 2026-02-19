<?php
session_start();
include("../db.php");

if(!isset($_SESSION['booking_data']))
{
    echo "No booking found";
    exit();
}

$data = $_SESSION['booking_data'];

$total = $data['total_price'];
$advance = round($total * 0.25, 2);
$remaining = $total - $advance;


// PAYMENT SUCCESS
if(isset($_POST['pay']))
{

$method = $_POST['payment_method'];

$upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : "";
$card_number = isset($_POST['card_number']) ? $_POST['card_number'] : "";
$expiry = isset($_POST['expiry']) ? $_POST['expiry'] : "";
$cvv = isset($_POST['cvv']) ? $_POST['cvv'] : "";


// BASIC SERVER VALIDATION

if($method=="UPI")
{
    if(empty($upi_id) || !preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/",$upi_id))
    {
        echo "<script>alert('Invalid UPI ID');</script>";
    }
    else
    {
        saveBooking($conn,$data,$total,$advance,$remaining,$method);
    }
}

else if($method=="Card")
{
    if(strlen($card_number)!=16 || strlen($cvv)!=3)
    {
        echo "<script>alert('Invalid Card Details');</script>";
    }
    else
    {
        saveBooking($conn,$data,$total,$advance,$remaining,$method);
    }
}

}


// FUNCTION TO SAVE BOOKING

function saveBooking($conn,$data,$total,$advance,$remaining,$method)
{

mysqli_query($conn,"
INSERT INTO bookings
(user_id,event_id,package_id,event_date,total_price,
advance_paid,remaining_amount,payment_status,payment_method,
food_ids,coverage_ids,capacity)

VALUES

('".$data['user_id']."',
'".$data['event_id']."',
'".$data['package_id']."',
'".$data['event_date']."',
'$total',
'$advance',
'$remaining',
'Advance Paid',
'$method',
'".$data['food_ids']."',
'".$data['coverage_ids']."',
'".$data['capacity']."')
");

unset($_SESSION['booking_data']);

echo "<script>
alert('Payment Successful! Booking Confirmed.');
window.location='my_bookings.php';
</script>";

exit();

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Payment</title>

<style>

body{
font-family:'Segoe UI';
background:linear-gradient(135deg,#1e3c72,#2a5298);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
margin:0;
color:white;
}

.container{
background:rgba(255,255,255,0.08);
padding:30px;
border-radius:15px;
width:420px;
box-shadow:0 0 20px rgba(0,0,0,0.3);
}

.amount-box{
background:rgba(0,0,0,0.2);
padding:10px;
border-radius:8px;
margin-bottom:10px;
}

.option{
display:block;
background:white;
color:black;
padding:10px;
margin-top:10px;
border-radius:8px;
cursor:pointer;
}

input[type=text], input[type=month], input[type=password]{
width:100%;
padding:10px;
margin-top:8px;
border-radius:8px;
border:none;
}

.hidden{
display:none;
}

button{
width:100%;
padding:12px;
margin-top:15px;
background:#00c6ff;
border:none;
border-radius:10px;
color:white;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0094cc;
}

.note{
margin-top:15px;
color:#ffd166;
}

</style>

</head>

<body>

<div class="container">

<h2>Payment</h2>

<div class="amount-box">
Total Amount: ₹ <?php echo number_format($total,2); ?>
</div>

<div class="amount-box">
Advance (25%): ₹ <?php echo number_format($advance,2); ?>
</div>

<div class="amount-box">
Remaining: ₹ <?php echo number_format($remaining,2); ?>
</div>


<form method="POST" onsubmit="return validateForm()">

<h3>Select Payment Method</h3>


<label class="option">
<input type="radio" name="payment_method" value="UPI" onclick="showUPI()" required>
UPI(Google Pay / PhonePe / Paytm)
</label>


<label class="option">
<input type="radio" name="payment_method" value="Card" onclick="showCard()">
Card
</label>


<!-- UPI FIELD -->

<div id="upiBox" class="hidden">

<input type="text"
name="upi_id"
id="upi_id"
placeholder="Enter UPI ID (example@upi)">

</div>


<!-- CARD FIELD -->

<div id="cardBox" class="hidden">

<input type="text"
name="card_number"
id="card_number"
placeholder="Card Number (16 digits)"
maxlength="16">

<input type="month"
name="expiry"
id="expiry">

<input type="password"
name="cvv"
id="cvv"
placeholder="CVV"
maxlength="3">

</div>


<button type="submit" name="pay">
Pay ₹ <?php echo number_format($advance,2); ?>
</button>

</form>


<div class="note">
Pay 25% advance now. Remaining after event completion.
</div>

</div>


<script>

function showUPI()
{
document.getElementById("upiBox").style.display="block";
document.getElementById("cardBox").style.display="none";
}

function showCard()
{
document.getElementById("upiBox").style.display="none";
document.getElementById("cardBox").style.display="block";
}


function validateForm()
{

let method=document.querySelector('input[name="payment_method"]:checked').value;

if(method=="UPI")
{
let upi=document.getElementById("upi_id").value;

let regex=/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/;

if(!regex.test(upi))
{
alert("Enter valid UPI ID");
return false;
}
}

if(method=="Card")
{
let card=document.getElementById("card_number").value;
let cvv=document.getElementById("cvv").value;

if(card.length!=16)
{
alert("Card number must be 16 digits");
return false;
}

if(cvv.length!=3)
{
alert("CVV must be 3 digits");
return false;
}
}

return true;

}

</script>

</body>
</html>
