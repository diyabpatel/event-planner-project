<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

$query = "
SELECT 
b.*,
e.event_name,
p.package_name

FROM bookings b

JOIN events e ON b.event_id = e.event_id
JOIN packages p ON b.package_id = p.package_id

WHERE b.user_id = $user_id

ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>My Bookings</title>

<style>

/* ===== BODY ===== */
body{
    margin:0;
    font-family:'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#ffffff;
}

/* ===== CONTAINER ===== */
.container{
    max-width:1100px;
    margin:40px auto 60px; /* 🔥 navbar niche tight */
    padding:20px;
}


h2{
    font-size:30px;
    font-weight:600;
    letter-spacing:0.5px;
    margin-top:5px;   /* 🔥 removes extra gap */
    margin-bottom:35px;
}

/* ===== CARD ===== */
.card{
    background:rgba(255,255,255,0.10);
    backdrop-filter:blur(14px);
    -webkit-backdrop-filter:blur(14px);
    border-radius:20px;
    padding:28px;
    margin-bottom:30px;
    box-shadow:
        0 25px 45px rgba(0,0,0,0.35),
        inset 0 0 0 1px rgba(255,255,255,0.08);
    transition:0.35s ease;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:
        0 35px 70px rgba(0,0,0,0.45),
        inset 0 0 0 1px rgba(255,255,255,0.12);
}

/* ===== CARD HEADER ===== */
.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.event-name{
    font-size:24px;
    font-weight:600;
    letter-spacing:0.3px;
}

.package{
    background:linear-gradient(135deg,#00c6ff,#0072ff);
    padding:7px 16px;
    border-radius:30px;
    font-size:13px;
    font-weight:500;
    box-shadow:0 5px 15px rgba(0,114,255,0.4);
}

/* ===== DETAILS GRID ===== */
.details{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:14px;
}

.detail-box{
    background:rgba(0,0,0,0.35);
    padding:14px 16px;
    border-radius:14px;
    font-size:14px;
    line-height:1.6;
    box-shadow:inset 0 0 0 1px rgba(255,255,255,0.06);
}

.detail-box b{
    font-weight:500;
    color:#cde9ff;
}

/* ===== STATUS BADGES ===== */
.status{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:500;
    letter-spacing:0.4px;
}

.upcoming{
    background:linear-gradient(135deg,#22c55e,#16a34a);
}

.completed{
    background:linear-gradient(135deg,#ef4444,#b91c1c);
}

.payment-paid{
    background:linear-gradient(135deg,#22c55e,#16a34a);
}

.payment-pending{
    background:linear-gradient(135deg,#f59e0b,#d97706);
}

/* ===== BUTTON ===== */
.btn{
    display:inline-block;
    margin-top:18px;
    padding:10px 22px;
    background:linear-gradient(135deg,#00c6ff,#0072ff);
    color:white;
    text-decoration:none;
    border-radius:30px;
    font-size:14px;
    font-weight:500;
    transition:0.3s ease;
    box-shadow:0 8px 22px rgba(0,114,255,0.45);
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 14px 30px rgba(0,114,255,0.6);
}

.btn.disabled{
    background:#6b7280;
    box-shadow:none;
    pointer-events:none;
}

</style>

</head>

<body>
<?php include("../navbar.php"); ?>

<div class="container">

<h2>My Bookings</h2>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

$event_date = $row['event_date'];

$change_last_date = date("Y-m-d", strtotime($event_date . " -5 days"));

$today = date("Y-m-d");

$is_edit_allowed = ($today <= $change_last_date);


// event status
$event_status = ($today <= $event_date)
? "<span class='status upcoming'>Upcoming</span>"
: "<span class='status completed'>Completed</span>";


// payment status
$payment_status = ($row['payment_status']=="Advance Paid")
? "<span class='status payment-pending'>Advance Paid</span>"
: "<span class='status payment-paid'>Fully Paid</span>";


echo "

<div class='card'>

<div class='card-header'>

<div class='event-name'>".$row['event_name']."</div>

<div class='package'>".$row['package_name']."</div>

</div>


<div class='details'>

<div class='detail-box'>
<b>Booking ID:</b><br>
".$row['booking_id']."
</div>

<div class='detail-box'>
<b>Capacity:</b><br>
".$row['capacity']."
</div>

<div class='detail-box'>
<b>Event Date:</b><br>
".$event_date."
</div>

<div class='detail-box'>
<b>Total Price:</b><br>
₹ ".$row['total_price']."
</div>

<div class='detail-box'>
<b>Advance Paid:</b><br>
₹ ".$row['advance_paid']."
</div>

<div class='detail-box'>
<b>Remaining Amount:</b><br>
₹ ".$row['remaining_amount']."
</div>

<div class='detail-box'>
<b>Payment Method:</b><br>
".$row['payment_method']."
</div>

<div class='detail-box'>
<b>Payment Status:</b><br>
".$payment_status."
</div>

<div class='detail-box'>
<b>Event Status:</b><br>
".$event_status."
</div>

<div class='detail-box'>
<b>Booked On:</b><br>
".$row['booking_date']."
</div>

</div>


<div style='margin-top:10px;color:#ffd166;'>
Changes allowed until: <b>".$change_last_date."</b>
</div>

";


if($is_edit_allowed)
{
echo "<a href='edit_booking.php?id=".$row['booking_id']."' class='btn'>Edit Booking</a>";
}
else
{
echo "<a class='btn disabled'>Edit Closed</a>";
}

echo "</div>";

}

}
else
{
echo "<div class='card'>No bookings found</div>";
}

?>

</div>

</body>
</html>
