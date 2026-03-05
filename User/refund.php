<?php
session_start();
include("../db.php");

if(!isset($_SESSION['refund_data'])){
echo "No refund data";
exit();
}

$data = $_SESSION['refund_data'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Refund</title>

<style>

body{
font-family:Segoe UI;
background:#0f172a;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.card{
width:420px;
background:#1e293b;
padding:30px;
border-radius:10px;
}

input,select{
width:100%;
padding:10px;
margin-top:10px;
}

button{
margin-top:20px;
padding:12px;
width:100%;
background:#3b82f6;
border:none;
color:white;
}

</style>
</head>

<body>

<div class="card">

<h2>Refund Process</h2>

<p>Refund Amount: ₹<?php echo $data['refund_amount']; ?></p>

<form method="POST">

<select name="method" required>
<option value="">Select Refund Method</option>
<option>UPI</option>
<option>Bank Transfer</option>
</select>

<input type="text" name="details" placeholder="Enter UPI ID / Bank Details" required>

<button name="submit">Confirm Refund</button>

</form>

</div>

</body>
</html>

<?php

if(isset($_POST['submit']))
{

$booking_id = $data['booking_id'];

mysqli_query($conn,"
UPDATE bookings SET
total_price='".$data['new_total']."',
advance_paid='".$data['new_advance']."',
remaining_amount='".$data['remaining_after']."',
capacity='".$data['capacity']."',
event_date='".$data['event_date']."',
venue_id='".$data['venue_id']."',
decoration_id='".$data['decoration_id']."',
seat_id='".$data['seat_id']."',
food_ids='".$data['food_ids']."',
coverage_ids='".$data['coverage_ids']."'
WHERE booking_id='$booking_id'
");

unset($_SESSION['refund_data']);

echo "<script>
alert('Refund processed successfully');
window.location='my_bookings.php';
</script>";

}
?>