<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../db.php");

/* ================= SUCCESS FLOW ================= */
if (isset($_SESSION['payment_success'])) {
    $showSuccess = true;
    // values avoid notices
    $total = $advance = $remaining = 0;
}
/* ================= NORMAL FLOW ================= */
else if (!isset($_SESSION['booking_data'])) {
    echo "No booking found";
    exit();
} else {
    $showSuccess = false;
    $data = $_SESSION['booking_data'];

    $total = $data['total_price'];
    $advance = round($total * 0.25, 2);
    $remaining = $total - $advance;
}

/* ================= PAYMENT SUBMIT ================= */
if (isset($_POST['pay'])) {

    $method = $_POST['payment_method'];

    $upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : "";
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : "";
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : "";

    if ($method == "UPI") {
        if (empty($upi_id) || !preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z]+$/", $upi_id)) {
            $error = "Invalid UPI ID";
        } else {
            saveBooking($conn, $_SESSION['booking_data'], $total, $advance, $remaining, $method);
        }
    }

    if ($method == "Card") {
        if (strlen($card_number) != 16 || strlen($cvv) != 3) {
            $error = "Invalid Card Details";
        } else {
            saveBooking($conn, $_SESSION['booking_data'], $total, $advance, $remaining, $method);
        }
    }
}

/* ================= SAVE BOOKING ================= */
function saveBooking($conn, $data, $total, $advance, $remaining, $method)
{
    mysqli_query($conn,"
        INSERT INTO bookings
        (user_id,event_id,package_id,capacity,event_date,total_price,
         advance_paid,remaining_amount,payment_status,payment_method,
         food_ids,coverage_ids)
        VALUES
        ('".$data['user_id']."',
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
         '".$data['coverage_ids']."')
    ");

    $bookingId = mysqli_insert_id($conn);

    // 🔔 EMAIL (SAFE – failure won't break payment)
    try {
        require 'send_mail.php';
        $u = mysqli_query($conn,"SELECT email FROM users WHERE user_id='".$data['user_id']."'");
        if ($u && mysqli_num_rows($u)) {
            $user = mysqli_fetch_assoc($u);
            @sendBookingMail($user['email'], $bookingId, $advance);
        }
    } catch (Exception $e) {
        // ignore email errors on localhost
    }

    unset($_SESSION['booking_data']);
    $_SESSION['payment_success'] = true;

    header("Location: payment.php");
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
font-family:Segoe UI;
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
height:100vh;display:flex;justify-content:center;align-items:center;
margin:0;color:white;
}
.container{
width:420px;background:rgba(255,255,255,0.12);
backdrop-filter:blur(18px);padding:30px;border-radius:18px;
box-shadow:0 20px 40px rgba(0,0,0,0.4);
}
.amount-box{
background:rgba(0,0,0,0.25);padding:12px;border-radius:10px;
margin-bottom:10px;display:flex;justify-content:space-between;
}
.option{
background:white;color:black;padding:12px;border-radius:10px;
margin-top:10px;display:flex;gap:10px;cursor:pointer;
}
input{width:100%;padding:10px;margin-top:10px;border-radius:8px;border:none;}
.hidden{display:none;}
button{
width:100%;padding:14px;margin-top:20px;border:none;border-radius:14px;
background:#00c6ff;color:white;font-size:16px;
}
.note{text-align:center;color:#ffd166;margin-top:10px;}
</style>
</head>

<body>

<div class="container" <?php if(isset($_SESSION['payment_success'])) echo 'style="display:none"'; ?>>

<h2 align="center">Secure Payment</h2>

<div class="amount-box"><span>Total</span><span>Rs. <?php echo number_format($total,2); ?></span></div>
<div class="amount-box"><span>Advance</span><span>Rs. <?php echo number_format($advance,2); ?></span></div>
<div class="amount-box"><span>Remaining</span><span>Rs. <?php echo number_format($remaining,2); ?></span></div>

<?php if(isset($error)) echo "<p style='color:#ffb3b3;text-align:center'>$error</p>"; ?>

<form method="POST">
<label class="option"><input type="radio" name="payment_method" value="UPI" required> UPI</label>
<label class="option"><input type="radio" name="payment_method" value="Card"> Card</label>

<div id="upiBox" class="hidden">
<input type="text" name="upi_id" placeholder="example@upi">
</div>

<div id="cardBox" class="hidden">
<input type="text" name="card_number" maxlength="16" placeholder="Card Number">
<input type="password" name="cvv" maxlength="3" placeholder="CVV">
</div>

<button name="pay">Pay Rs. <?php echo number_format($advance,2); ?></button>
</form>

<div class="note">Pay 25% advance now</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['payment_success'])): ?>
<script>
Swal.fire({
    title:'Payment Successful',
    text:'Booking confirmed',
    icon:'success',
    showCancelButton:true,
    confirmButtonText:'Download Receipt',
    cancelButtonText:'My Bookings'
}).then((r)=>{
    if(r.isConfirmed) location='receipt.php';
    else location='my_bookings.php';
});
</script>
<?php unset($_SESSION['payment_success']); endif; ?>

<script>
document.querySelectorAll("input[name=payment_method]").forEach(function(el){
    el.onclick=function(){
        document.getElementById("upiBox").style.display = (el.value=="UPI")?"block":"none";
        document.getElementById("cardBox").style.display = (el.value=="Card")?"block":"none";
    };
});
</script>

</body>
</html>