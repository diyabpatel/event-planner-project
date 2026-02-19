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

$advance = $total * 0.25;
$remaining = $total - $advance;


// PAYMENT SUCCESS
if(isset($_POST['pay']))
{

$method = $_POST['payment_method'];

// insert booking
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
alert('Payment Successful! Booking Confirmed');
window.location='my_bookings.php';
</script>";

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Payment</title>

<style>

body{
font-family:Arial;
background:#1e3c72;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.container{
background:rgba(255,255,255,0.1);
padding:30px;
border-radius:10px;
width:400px;
}

.option{
background:white;
color:black;
padding:10px;
margin:10px 0;
cursor:pointer;
border-radius:5px;
}

button{
width:100%;
padding:10px;
background:#00c6ff;
border:none;
color:white;
margin-top:15px;
}

</style>

</head>

<body>

<div class="container">

<h2>Payment</h2>

<p>Total Amount: ₹ <?php echo $total; ?></p>

<p>Advance (25%): ₹ <?php echo $advance; ?></p>

<p>Remaining: ₹ <?php echo $remaining; ?></p>

<form method="POST">

<h3>Select Payment Method</h3>

<label class="option">
<input type="radio" name="payment_method" value="UPI" required>
UPI
</label>

<label class="option">
<input type="radio" name="payment_method" value="Card">
Credit / Debit Card
</label>

<label class="option">
<input type="radio" name="payment_method" value="Net Banking">
Net Banking
</label>

<label class="option">
<input type="radio" name="payment_method" value="Cash">
Cash
</label>

<button type="submit" name="pay">
Pay ₹ <?php echo $advance; ?>
</button>

</form>

</div>

</body>
</html>
