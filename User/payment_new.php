<?php
session_start();
include("../db.php");

/* ✅ FORCE UTF-8 */
header('Content-Type: text/html; charset=utf-8');

/* ✅ LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(!isset($_SESSION['booking_data']))
{
    echo "No booking data";
    exit();
}

$data = $_SESSION['booking_data'];
$user_id = $_SESSION['user_id'];

$total = $data['total_price'];

/* ✅ NEW ADVANCE CALCULATION FUNCTION */
function calculateAdvance($total)
{
    if($total <= 50000){
        $advance = 0.5 * $total; // 50%
    }
    elseif($total <= 200000){
        $advance = 0.3 * $total; // 30%
    }
    elseif($total <= 500000){
        $advance = 0.2 * $total; // 20%
    }
    else{
        $advance = 0.1 * $total; // 10%
    }

    // ✅ MAX CAP ₹1,00,000
    if($advance > 100000){
        $advance = 100000;
    }

    return round($advance, 2);
}

/* ✅ APPLY NEW LOGIC */
$advance = calculateAdvance($total);
$remaining = $total - $advance;

if(isset($_POST['pay']))
{
    $payer_name = trim($_POST['payer_name']);

    if($payer_name == "")
    {
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

        /* ✅ BOOKINGS INSERT */
 $new_advance = $advance;
$remaining = $total - $new_advance;

mysqli_query($conn,"
INSERT INTO bookings
(user_id,event_id,package_id,capacity,event_date,total_price,
advance_paid,remaining_amount,
payment_status,food_ids,coverage_ids)
VALUES
(
'$user_id',
'".$data['event_id']."',
'".$data['package_id']."',
'".$data['capacity']."',
'".$data['event_date']."',
'$total',
'$new_advance',
'$remaining',
'Verification Pending',
'".$data['food_ids']."',
'".$data['coverage_ids']."'
)
");

        $booking_id=mysqli_insert_id($conn);

        /* ✅ PAYMENTS INSERT */
        mysqli_query($conn,"
        INSERT INTO payments
        (booking_id,user_id,payment_type,payment_method,payer_name,
        amount,proof_image,aadhaar,pan,
        proof_status,aadhaar_status,pan_status,payment_status)
        VALUES
        (
        '$booking_id',
        '$user_id',
        'Advance',
        'UPI',
        '$payer_name',
        '$advance',
        '$proof_image',
        '$aadhaar',
        '$pan',
        0,0,0,
        'Verification Pending'
        )
        ");

        unset($_SESSION['booking_data']);

        echo "<script>
        alert('Payment submitted');
        window.location='receipt.php?booking_id=$booking_id';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Advance Payment</title>

<style>
body{
margin:0;
font-family:Segoe UI;
background:#0f172a;
display:flex;
justify-content:center;
align-items:flex-start;
padding:30px;
color:white;
}

.card{
width:100%;
max-width:500px;
background:#1e293b;
padding:25px;
border-radius:10px;
}

.section{
background:#020617;
padding:15px;
margin-bottom:15px;
border-radius:6px;
}

.row{
display:flex;
justify-content:space-between;
margin:8px 0;
}

input{
width:100%;
padding:10px;
margin-top:10px;
border-radius:6px;
border:none;
}

button{
width:100%;
padding:12px;
background:#3b82f6;
border:none;
color:white;
margin-top:20px;
cursor:pointer;
}

.qr{text-align:center;}
.small{font-size:12px;color:#94a3b8;text-align:center;}
</style>
</head>

<body>

<div class="card">

<h2>Advance Payment</h2>

<div class="section">
<div class="row"><span>Total</span><span>&#8377; <?php echo htmlspecialchars($total); ?></span></div>
<div class="row"><span>Advance</span><span>&#8377; <?php echo htmlspecialchars($advance); ?></span></div>
<div class="row"><span>Remaining</span><span>&#8377; <?php echo htmlspecialchars($remaining); ?></span></div>
</div>

<div class="section">
<b>Pay Using UPI</b>

<?php
$upi_id = "dixita3286@okicici";   // 🔥 same as Angular
$upi_name = "Dixita";

$upi_link = "upi://pay?pa=$upi_id&pn=$upi_name&am=$advance&cu=INR";
?>

<div class="row">
  <span>UPI</span>
  <span><?php echo $upi_id; ?></span>
</div>

<div class="row">
  <span>Amount</span>
  <span>&#8377; <?php echo htmlspecialchars($advance); ?></span>
</div>

<div class="qr">
  <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($upi_link); ?>">
</div>

<p class="small">Pay and upload documents</p>
</div>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="payer_name" placeholder="Your Name" required>

<label>Screenshot</label>
<input type="file" name="proof_image" required>

<label>Aadhaar</label>
<input type="file" name="aadhaar" required>

<label>PAN</label>
<input type="file" name="pan" required>

<button name="pay">Submit</button>

</form>

</div>

</body>
</html>