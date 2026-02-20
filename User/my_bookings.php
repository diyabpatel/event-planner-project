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
    background:linear-gradient(135deg,#0b0f1a,#121a2e,#1a2742);
    color:#eaeaff;
}

/* ===== CONTAINER ===== */
.container{
    max-width:1150px;
    margin:50px auto 70px;
    padding:25px;
}

/* ===== HEADING ===== */
h2{
    font-size:32px;
    font-weight:600;
    letter-spacing:0.8px;
    margin-bottom:40px;
    text-align:center;
    background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* ===== CARD ===== */
.card{
    background:rgba(255,255,255,0.06);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    border-radius:22px;
    padding:30px;
    margin-bottom:35px;
    box-shadow:
        0 30px 70px rgba(0,0,0,0.65),
        inset 0 0 0 1px rgba(255,255,255,0.06);
    transition:0.4s ease;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:
        0 45px 90px rgba(0,0,0,0.75),
        inset 0 0 0 1px rgba(122,162,255,0.25);
}

/* ===== CARD HEADER ===== */
.card-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.event-name{
    font-size:26px;
    font-weight:600;
    letter-spacing:0.5px;
}

.package{
    background:linear-gradient(135deg,#7aa2ff,#4f7cff);
    padding:8px 18px;
    border-radius:40px;
    font-size:13px;
    font-weight:500;
    box-shadow:0 10px 25px rgba(122,162,255,0.4);
}

/* ===== DETAILS GRID ===== */
.details{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:18px;
}

.detail-box{
    background:rgba(0,0,0,0.45);
    padding:16px 18px;
    border-radius:16px;
    font-size:14px;
    line-height:1.7;
    box-shadow:inset 0 0 0 1px rgba(255,255,255,0.05);
    transition:0.3s ease;
}

.detail-box:hover{
    background:rgba(122,162,255,0.12);
    box-shadow:inset 0 0 0 1px rgba(122,162,255,0.35);
}

.detail-box b{
    font-weight:500;
    color:#9bb6ff;
}

/* ===== STATUS BADGES ===== */
.status{
    display:inline-block;
    padding:7px 16px;
    border-radius:30px;
    font-size:12px;
    font-weight:500;
    letter-spacing:0.5px;
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

/* ===== CHANGE DATE TEXT ===== */
.card div[style]{
    margin-top:15px !important;
    color:#ffd166 !important;
    font-size:14px;
}

/* ===== BUTTON ===== */
.btn{
    display:inline-block;
    margin-top:22px;
    padding:11px 26px;
    background:linear-gradient(135deg,#7aa2ff,#4f7cff);
    color:white;
    text-decoration:none;
    border-radius:40px;
    font-size:14px;
    font-weight:500;
    transition:0.35s ease;
    box-shadow:0 12px 30px rgba(122,162,255,0.5);
}

.btn:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 40px rgba(122,162,255,0.7);
}

.btn.disabled{
    background:#4b5563;
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
