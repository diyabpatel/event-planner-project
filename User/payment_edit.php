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

/* 🔥 IMPORTANT LOGIC */
$new_advance = $old_advance; // SAME advance rakho
$new_remaining = $new_total - $new_advance;

if(isset($_POST['process']))
{

/* ================= UPDATE BOOKING ================= */

$query = "
UPDATE bookings SET

capacity='".$data['capacity']."',
event_date='".$data['event_date']."',

total_price='".$new_total."',
advance_paid='".$new_advance."',
remaining_amount='".$new_remaining."',

food_ids='".$data['food_ids']."',
coverage_ids='".$data['coverage_ids']."'

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
<title>Update Booking</title>
<meta charset="UTF-8">

<style>
body{
margin:0;
font-family:Segoe UI;
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

.row{
display:flex;
justify-content:space-between;
padding:8px 0;
}

.amount{
font-weight:600;
color:#38bdf8;
}

.note{
text-align:center;
font-size:14px;
color:#94a3b8;
margin-top:10px;
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
</style>

</head>

<body>

<div class="card">

<h2>Update Booking</h2>

<div class="section">

<div class="row">
<span>Old Total</span>
<span class="amount">₹ <?php echo number_format($old_total,2); ?></span>
</div>

<div class="row">
<span>Advance Paid</span>
<span class="amount">₹ <?php echo number_format($old_advance,2); ?></span>
</div>

</div>

<div class="section">

<div class="row">
<span>New Total</span>
<span class="amount">₹ <?php echo number_format($new_total,2); ?></span>
</div>

<div class="row">
<span>Remaining</span>
<span class="amount">₹ <?php echo number_format($new_remaining,2); ?></span>
</div>

</div>

<div class="note">
No extra payment required. Your previous advance is adjusted.
</div>

<form method="POST">
<button name="process">Confirm Update</button>
</form>

</div>

</body>
</html>