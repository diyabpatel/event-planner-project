<?php
session_start();
include("../db.php");

header('Content-Type: text/html; charset=utf-8');

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(!isset($_SESSION['booking_data'])){
    echo "No booking data";
    exit();
}

$data = $_SESSION['booking_data'];
$user_id = $_SESSION['user_id'];
$total = $data['total_price'];

/* ADVANCE */
function calculateAdvance($total){
    if($total <= 50000) $advance = 0.5 * $total;
    elseif($total <= 200000) $advance = 0.3 * $total;
    elseif($total <= 500000) $advance = 0.2 * $total;
    else $advance = 0.1 * $total;

    if($advance > 100000) $advance = 100000;

    return round($advance, 2);
}

$advance = calculateAdvance($total);
$remaining = $total - $advance;

/* SUBMIT */
if(isset($_POST['pay']))
{
    $payer_name = trim($_POST['payer_name']);
    $user_upi = trim($_POST['user_upi']);

    /* UPI VALIDATION */
    if(!preg_match("/^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$/", $user_upi)){
        echo "<script>alert('Invalid UPI');</script>";
        exit();
    }

    if($payer_name == ""){
        echo "<script>alert('Enter name');</script>";
    }
    else
    {
        $folder = "../uploads/payment_docs/";
        if(!file_exists($folder)) mkdir($folder,0777,true);

        $proof_image="";
        if($_FILES['proof_image']['name']!=""){
            $file=time()."_".$_FILES['proof_image']['name'];
            move_uploaded_file($_FILES['proof_image']['tmp_name'],$folder.$file);
            $proof_image="uploads/payment_docs/".$file;
        }

        $aadhaar="";
        if($_FILES['aadhaar']['name']!=""){
            $file="aadhaar_".time()."_".$_FILES['aadhaar']['name'];
            move_uploaded_file($_FILES['aadhaar']['tmp_name'],$folder.$file);
            $aadhaar="uploads/payment_docs/".$file;
        }

        $pan="";
        if($_FILES['pan']['name']!=""){
            $file="pan_".time()."_".$_FILES['pan']['name'];
            move_uploaded_file($_FILES['pan']['tmp_name'],$folder.$file);
            $pan="uploads/payment_docs/".$file;
        }

        /* ✅ SAFE VALUES (ADDED) */
        $venue_id = isset($data['venue_id']) ? $data['venue_id'] : 0;
        $decoration_id = isset($data['decoration_id']) ? $data['decoration_id'] : 0;
        $seat_id = isset($data['seat_id']) ? $data['seat_id'] : 0;

        /* ✅ BOOKINGS (FIXED) */
        mysqli_query($conn,"INSERT INTO bookings
        (user_id,event_id,package_id,capacity,event_date,total_price,
        venue_id,decoration_id,seat_id,
        advance_paid,remaining_amount,payment_status,food_ids,coverage_ids)
        VALUES
        ('$user_id','".$data['event_id']."','".$data['package_id']."',
        '".$data['capacity']."','".$data['event_date']."','$total',
        '$venue_id','$decoration_id','$seat_id',
        '$advance','$remaining','Pending',
        '".$data['food_ids']."','".$data['coverage_ids']."')"); // ✅ FIXED

        $booking_id = mysqli_insert_id($conn);

        /* ✅ PAYMENTS (STORE UPI HERE) */
        mysqli_query($conn,"INSERT INTO payments
        (booking_id,user_id,payment_type,payment_method,payer_name,
        upi_id,amount,proof_image,aadhaar,pan,payment_status)
        VALUES
        ('$booking_id','$user_id','Advance','UPI','$payer_name',
        '$user_upi','$advance','$proof_image','$aadhaar','$pan','Pending')");

        unset($_SESSION['booking_data']);

        echo "
<!DOCTYPE html>
<html>
<head>
<title>Payment Submitted</title>

<style>
body{
margin:0;
font-family:'Poppins', sans-serif;
background:#faf7ff;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.popup{
background:#ffffff;
padding:30px;
border-radius:16px;
text-align:center;
max-width:400px;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
animation:fadeIn 0.5s ease;
}

h2{
color:#7c3aed;
margin-bottom:10px;
}

p{
color:#555;
font-size:14px;
margin-bottom:20px;
}

button{
padding:12px 20px;
border:none;
border-radius:10px;
background:#7c3aed;
color:white;
cursor:pointer;
font-weight:500;
}

button:hover{
background:#6d28d9;
}

@keyframes fadeIn{
from{opacity:0; transform:translateY(20px);}
to{opacity:1; transform:translateY(0);}
}
</style>

</head>

<body>

<div class='popup'>
<h2>Payment Submitted ✅</h2>
<p>Your payment has been successfully submitted and is currently under administrative verification.</p>

<button onclick=\"window.location.href='my_bookings.php'\">
Go to My Bookings
</button>
</div>

</body>
</html>
";
exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Advance Payment</title>

<style>
body{
margin:0;
font-family:'Segoe UI', sans-serif;
background:#faf7ff;
display:flex;
justify-content:center;
align-items:flex-start;
padding:40px;
}

.card{
width:100%;
max-width:500px;
background:#ffffff;
padding:25px;
border-radius:14px;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
}

h2{
color:#7c3aed;
margin-bottom:15px;
}

.section{
background:#f3e8ff;
padding:15px;
margin-bottom:15px;
border-radius:10px;
}

.row{
display:flex;
justify-content:space-between;
margin:8px 0;
color:#555;
}

.row span:last-child{
color:#7c3aed;
font-weight:600;
}

form{
display:flex;
flex-direction:column;
gap:10px;
}

label{
color:#555;
}

input{
width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ddd;
}

input:focus{
border-color:#7c3aed;
box-shadow:0 0 5px rgba(124,58,237,0.3);
outline:none;
}

button{
width:100%;
padding:12px;
background:#7c3aed;
border:none;
color:white;
border-radius:8px;
cursor:pointer;
}

button:hover{
background:#6d28d9;
}

.qr{
text-align:center;
margin-top:10px;
}

.qr img{
border-radius:10px;
border:2px solid #7c3aed;
}

.small{
text-align:center;
font-size:12px;
color:#7c3aed;
margin-top:5px;
}
</style>

</head>

<body>

<div class="card">

<h2>Advance Payment</h2>

<div class="section">
<div class="row"><span>Total</span><span>₹ <?php echo $total; ?></span></div>
<div class="row"><span>Advance</span><span>₹ <?php echo $advance; ?></span></div>
<div class="row"><span>Remaining</span><span>₹ <?php echo $remaining; ?></span></div>
</div>

<div class="section">
<b>Pay Using UPI</b>

<?php
$display_upi = "eventhub@okicici";
$real_upi = "dixita3286@okicici";
$upi_name = "Event Hub";

$upi_link = "upi://pay?pa=$real_upi&pn=$upi_name&am=$advance&cu=INR";
?>

<div class="row">
<span>UPI</span>
<span><?php echo $display_upi; ?></span>
</div>

<div class="row">
<span>Amount</span>
<span>₹ <?php echo $advance; ?></span>
</div>

<div class="qr">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($upi_link); ?>">
</div>

<p class="small">⚠️ Pay using QR only</p>
</div>

<form method="POST" enctype="multipart/form-data" id="paymentForm">

<input type="text" name="payer_name" placeholder="Your Name" required>

<label>Enter Your UPI ID</label>
<input type="text" name="user_upi" id="user_upi" placeholder="example@okicici" required>

<label>Screenshot</label>
<input type="file" name="proof_image" required>

<label>Aadhaar</label>
<input type="file" name="aadhaar" required>

<label>PAN</label>
<input type="file" name="pan" required>

<button name="pay">Submit</button>

</form>

</div>

<script>
document.getElementById("paymentForm").addEventListener("submit", function(e) {
    let upi = document.getElementById("user_upi").value.trim();

    let pattern = /^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$/;

    if(!pattern.test(upi)){
        alert("❌ Invalid UPI ID");
        e.preventDefault();
        return;
    }

    let allowed = ["okicici","oksbi","okaxis","ybl","paytm","ibl","upi"];
    let domain = upi.split("@")[1];

    if(!allowed.includes(domain)){
        alert("❌ Enter valid UPI ID");
        e.preventDefault();
    }
});
</script>

</body>
</html>