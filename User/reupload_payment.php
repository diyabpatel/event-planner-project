<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

/* FETCH OLD DATA */

$q = mysqli_query($conn,"
SELECT p.*, b.total_price 
FROM payments p
JOIN bookings b ON p.booking_id=b.booking_id
WHERE p.booking_id='$booking_id'
");

$data = mysqli_fetch_assoc($q);

if(isset($_POST['update']))
{
    $payer_name = $_POST['payer_name'];

    $folder = "../uploads/payment_docs/";

    /* UPDATE FILES IF NEW UPLOADED */

    if($_FILES['proof_image']['name']!=""){
        $file=time()."_".$_FILES['proof_image']['name'];
        move_uploaded_file($_FILES['proof_image']['tmp_name'],$folder.$file);
        $proof = "uploads/payment_docs/".$file;

        mysqli_query($conn,"UPDATE payments SET proof_image='$proof' WHERE booking_id='$booking_id'");
    }

    if($_FILES['aadhaar']['name']!=""){
        $file="aadhaar_".time()."_".$_FILES['aadhaar']['name'];
        move_uploaded_file($_FILES['aadhaar']['tmp_name'],$folder.$file);
        $aadhaar = "uploads/payment_docs/".$file;

        mysqli_query($conn,"UPDATE payments SET aadhaar='$aadhaar' WHERE booking_id='$booking_id'");
    }

    if($_FILES['pan']['name']!=""){
        $file="pan_".time()."_".$_FILES['pan']['name'];
        move_uploaded_file($_FILES['pan']['tmp_name'],$folder.$file);
        $pan = "uploads/payment_docs/".$file;

        mysqli_query($conn,"UPDATE payments SET pan='$pan' WHERE booking_id='$booking_id'");
    }

    /* RESET STATUS */

    mysqli_query($conn,"
    UPDATE payments SET 
    proof_status=0,
    aadhaar_status=0,
    pan_status=0
    WHERE booking_id='$booking_id'
    ");

    mysqli_query($conn,"
    UPDATE bookings SET 
    payment_status='Verification Pending',
    notification='Documents re-uploaded, waiting for verification',
    is_read=0
    WHERE booking_id='$booking_id'
    ");

    echo "<script>
    alert('Documents re-uploaded successfully');
    window.location='my_bookings.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Re-upload Documents</title>

<style>
body{
font-family:Segoe UI;
background:#0f172a;
color:white;
display:flex;
justify-content:center;
padding:40px;
}

.card{
background:#1e293b;
padding:25px;
width:400px;
border-radius:10px;
}

img{
width:100px;
margin-top:5px;
}

input{
width:100%;
padding:10px;
margin-top:10px;
}

button{
width:100%;
padding:12px;
margin-top:15px;
background:#3b82f6;
border:none;
color:white;
}
</style>

</head>

<body>

<div class="card">

<h2>Re-upload Documents</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="payer_name" 
value="<?php echo $data['payer_name']; ?>" required>

<p>Current Screenshot:</p>
<img src="../<?php echo $data['proof_image']; ?>">

<input type="file" name="proof_image">

<p>Current Aadhaar:</p>
<img src="../<?php echo $data['aadhaar']; ?>">

<input type="file" name="aadhaar">

<p>Current PAN:</p>
<img src="../<?php echo $data['pan']; ?>">

<input type="file" name="pan">

<button name="update">Update Documents</button>

</form>

</div>

</body>
</html>