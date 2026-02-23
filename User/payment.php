<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

/* ================= VARIABLES ================= */

$isExtraPayment = false;
$isNewBooking = false;

$previous_total = 0;
$previous_advance = 0;
$previous_remaining = 0;

$new_total = 0;
$new_advance = 0;
$new_remaining = 0;

$extra_amount = 0;

/* ================= EXTRA PAYMENT FLOW ================= */

if(isset($_SESSION['extra_payment']))
{
    $isExtraPayment = true;

    $data = $_SESSION['extra_payment'];

    $booking_id = $data['booking_id'];

    $q = mysqli_query($conn,"SELECT * FROM bookings WHERE booking_id=$booking_id");
    $booking = mysqli_fetch_assoc($q);

    $previous_total = $booking['total_price'];
    $previous_advance = $booking['advance_paid'];
    $previous_remaining = $booking['remaining_amount'];

    $new_total = $data['new_total'];
    $new_advance = round($new_total * 0.25, 2);
    $new_remaining = $new_total - $new_advance;

    $extra_amount = $new_advance - $previous_advance;
}

/* ================= NEW BOOKING FLOW ================= */

else if(isset($_SESSION['booking_data']))
{
    $isNewBooking = true;

    $data = $_SESSION['booking_data'];

    $new_total = $data['total_price'];
    $new_advance = round($new_total * 0.25, 2);
    $new_remaining = $new_total - $new_advance;

    $extra_amount = $new_advance;
}

else
{
    echo "<h2 style='color:white;text-align:center;margin-top:50px'>No payment found</h2>";
    exit();
}


/* ================= PAYMENT PROCESS ================= */

if(isset($_POST['pay']))
{
    $method = $_POST['payment_method'];

    $upi = isset($_POST['upi_id']) ? $_POST['upi_id'] : "";
    $card = isset($_POST['card_number']) ? $_POST['card_number'] : "";
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : "";

    if($method=="UPI")
    {
        if(!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/",$upi))
        {
            $error="Invalid UPI ID";
        }
        else
        {
            processPayment($conn,$method);
        }
    }

    if($method=="Card")
    {
        if(strlen($card)!=16 || strlen($cvv)!=3)
        {
            $error="Invalid card details";
        }
        else
        {
            processPayment($conn,$method);
        }
    }
}


/* ================= FUNCTION ================= */

function processPayment($conn,$method)
{
    global $isExtraPayment,$isNewBooking,$new_total,$new_advance,$new_remaining;

    if($isExtraPayment)
    {
        $data=$_SESSION['extra_payment'];

        mysqli_query($conn,"
        UPDATE bookings SET
        total_price='$new_total',
        advance_paid='$new_advance',
        remaining_amount='$new_remaining',
        payment_method='$method'
        WHERE booking_id='".$data['booking_id']."'
        ");

        unset($_SESSION['extra_payment']);
    }

    if($isNewBooking)
    {
        $data=$_SESSION['booking_data'];

        mysqli_query($conn,"
        INSERT INTO bookings
        (user_id,event_id,package_id,capacity,event_date,total_price,
        advance_paid,remaining_amount,payment_status,payment_method,
        food_ids,coverage_ids)

        VALUES
        (
        '".$data['user_id']."',
        '".$data['event_id']."',
        '".$data['package_id']."',
        '".$data['capacity']."',
        '".$data['event_date']."',
        '$new_total',
        '$new_advance',
        '$new_remaining',
        'Advance Paid',
        '$method',
        '".$data['food_ids']."',
        '".$data['coverage_ids']."'
        )
        ");

        unset($_SESSION['booking_data']);
    }

    $_SESSION['payment_success']=true;

    header("Location: payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Secure Payment</title>

<style>

body
{
margin:0;
font-family:"Segoe UI",Roboto,Arial,sans-serif;

background:
linear-gradient(rgba(8,20,50,0.75), rgba(8,20,50,0.75)),
url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;

display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
color:white;
}

/* CARD */

.card
{
width:480px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(18px);

padding:35px;
border-radius:18px;

box-shadow:
0 8px 32px rgba(0,0,0,0.45),
inset 0 0 0 1px rgba(255,255,255,0.08);

animation:fadeIn 0.5s ease;
}

@keyframes fadeIn
{
from {opacity:0; transform:translateY(20px);}
to {opacity:1; transform:translateY(0);}
}

h2
{
text-align:center;
margin-bottom:25px;
font-weight:600;
}

/* SECTIONS */

.section
{
background:rgba(255,255,255,0.06);
padding:16px;
border-radius:12px;
margin-bottom:18px;
border:1px solid rgba(255,255,255,0.08);
}

.row
{
display:flex;
justify-content:space-between;
margin:10px 0;
font-size:15px;
}

.row span:last-child
{
font-weight:600;
}

/* EXTRA PAYMENT */

.extra
{
background:linear-gradient(135deg,#ff4e50,#f00000);
padding:14px;
border-radius:10px;
text-align:center;
margin-bottom:18px;
font-weight:600;
font-size:16px;
}

/* METHODS */

.method
{
background:white;
color:black;
padding:14px;
border-radius:10px;
margin-top:10px;

display:flex;
justify-content:space-between;
align-items:center;

cursor:pointer;
transition:0.25s;
}

.method:hover
{
transform:scale(1.02);
}

/* INPUT */

input[type=text],
input[type=password]
{
width:100%;
padding:12px;
margin-top:10px;

border-radius:8px;
border:none;
outline:none;

font-size:14px;
}

/* BUTTON */

button
{
width:100%;
padding:15px;
margin-top:20px;

background:linear-gradient(135deg,#00c6ff,#0072ff);

border:none;
border-radius:10px;

color:white;
font-size:16px;
font-weight:600;

cursor:pointer;
transition:0.25s;
}

button:hover
{
transform:translateY(-2px);
}

/* ERROR */

.error
{
background:#ff4444;
padding:10px;
border-radius:8px;
margin-bottom:15px;
text-align:center;
}

.hidden
{
display:none;
}

</style>

</head>

<body>

<div class="card">

<h2>Secure Payment</h2>


<?php if($isExtraPayment){ ?>

<div class="section">

<b>Previous Payment</b>

<div class="row">
<span>Total</span>
<span>₹ <?php echo number_format($previous_total,2); ?></span>
</div>

<div class="row">
<span>Advance Paid</span>
<span>₹ <?php echo number_format($previous_advance,2); ?></span>
</div>

<div class="row">
<span>Remaining</span>
<span>₹ <?php echo number_format($previous_remaining,2); ?></span>
</div>

</div>

<?php } ?>


<div class="section">

<b>New Payment</b>

<div class="row">
<span>Total</span>
<span>₹ <?php echo number_format($new_total,2); ?></span>
</div>

<div class="row">
<span>Advance (25%)</span>
<span>₹ <?php echo number_format($new_advance,2); ?></span>
</div>

<div class="row">
<span>Remaining</span>
<span>₹ <?php echo number_format($new_remaining,2); ?></span>
</div>

</div>


<div class="extra">
Extra Payment Required: ₹ <?php echo number_format($extra_amount,2); ?>
</div>


<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>


<form method="POST">

<label class="method">
UPI
<input type="radio" name="payment_method" value="UPI" required onclick="showUPI()">
</label>

<div id="upiBox" class="hidden">
<input type="text" name="upi_id" placeholder="example@upi">
</div>


<label class="method">
Card
<input type="radio" name="payment_method" value="Card" onclick="showCard()">
</label>

<div id="cardBox" class="hidden">
<input type="text" name="card_number" placeholder="Card Number">
<input type="password" name="cvv" placeholder="CVV">
</div>


<button name="pay">
Pay ₹ <?php echo number_format($extra_amount,2); ?>
</button>

</form>

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

</script>

</body>
</html>