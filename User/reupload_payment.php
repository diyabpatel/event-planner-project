<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

/* FETCH DATA */
$q = mysqli_query($conn,"
SELECT p.*, b.total_price 
FROM payments p
JOIN bookings b ON p.booking_id=b.booking_id
WHERE p.booking_id='$booking_id'
");

$data = mysqli_fetch_assoc($q);

if(isset($_POST['update']))
{
    $folder = "../uploads/payment_docs/";
    $updated = false; // 🔥 track change

    /* ===== PROOF ===== */
    if($_FILES['proof_image']['name']!=""){
        $file=time()."_".$_FILES['proof_image']['name'];
        move_uploaded_file($_FILES['proof_image']['tmp_name'],$folder.$file);
        $proof = "uploads/payment_docs/".$file;

        mysqli_query($conn,"
        UPDATE payments 
        SET proof_image='$proof', proof_status=0 
        WHERE booking_id='$booking_id'
        ");

        $updated = true;
    }

    /* ===== AADHAAR ===== */
    if($_FILES['aadhaar']['name']!=""){
        $file="aadhaar_".time()."_".$_FILES['aadhaar']['name'];
        move_uploaded_file($_FILES['aadhaar']['tmp_name'],$folder.$file);
        $aadhaar = "uploads/payment_docs/".$file;

        mysqli_query($conn,"
        UPDATE payments 
        SET aadhaar='$aadhaar', aadhaar_status=0 
        WHERE booking_id='$booking_id'
        ");

        $updated = true;
    }

    /* ===== PAN ===== */
    if($_FILES['pan']['name']!=""){
        $file="pan_".time()."_".$_FILES['pan']['name'];
        move_uploaded_file($_FILES['pan']['tmp_name'],$folder.$file);
        $pan = "uploads/payment_docs/".$file;

        mysqli_query($conn,"
        UPDATE payments 
        SET pan='$pan', pan_status=0 
        WHERE booking_id='$booking_id'
        ");

        $updated = true;
    }

    /* 🔥 ONLY IF SOMETHING UPLOADED */
    if($updated){

        mysqli_query($conn,"
        UPDATE payments 
        SET payment_status='Pending'
        WHERE booking_id='$booking_id'
        ");

        mysqli_query($conn,"
        UPDATE bookings SET 
        payment_status='Pending',
        notification='Documents re-uploaded, waiting for verification',
        is_read=0
        WHERE booking_id='$booking_id'
        ");

        echo "<script>
        alert('Documents re-uploaded successfully');
        window.location='my_bookings.php';
        </script>";
    }
    else{
        echo "<script>alert('Please upload at least one document');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Re-upload Documents</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#fdfbff,#f3f0ff);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

/* CARD */
.card{
    background:rgba(255,255,255,0.75);
    backdrop-filter:blur(18px);
    padding:30px;
    width:420px;
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.3);
    box-shadow:0 20px 50px rgba(124,58,237,0.15);
    transition:0.4s ease;
}

.card:hover{
    transform:translateY(-6px) scale(1.01);
    box-shadow:0 30px 70px rgba(124,58,237,0.25);
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:20px;
    color:#5b21b6;
    font-weight:600;
}

/* INPUT */
input[type="text"],
input[type="file"]{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#7c3aed;
    box-shadow:0 0 10px rgba(124,58,237,0.3);
}

/* IMAGE */
img{
    width:100px;
    border-radius:12px;
    margin-top:8px;
    transition:0.3s ease;
    cursor:pointer;
}

img:hover{
    transform:scale(1.15);
    box-shadow:0 10px 25px rgba(124,58,237,0.3);
}

/* LABEL */
p{
    margin-top:14px;
    font-size:14px;
    font-weight:500;
    color:#4c1d95;
}

/* STATUS */
.status-approved{
    color:#22c55e;
    font-weight:600;
}

.status-rejected{
    color:#ef4444;
    font-weight:600;
}

/* BUTTON */
button{
    width:100%;
    padding:14px;
    margin-top:20px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:white;
    font-weight:600;
    letter-spacing:0.5px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 30px rgba(124,58,237,0.4);
}

/* FILE INPUT CUSTOM LOOK */
input[type="file"]{
    background:#f5f3ff;
    border:1px dashed #a78bfa;
    cursor:pointer;
}

input[type="file"]:hover{
    background:#ede9fe;
}

/* SMOOTH ANIMATION */
.card, img, button, input{
    transition:all 0.3s ease;
}

</style>

</head>

<body>

<div class="card">

<h2>Re-upload Documents</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="payer_name" 
value="<?php echo $data['payer_name']; ?>" required>

<!-- ===== PROOF ===== -->
<p>Payment Screenshot:</p>
<img src="../<?php echo $data['proof_image']; ?>">

<?php if($data['proof_status']==2){ ?>
<input type="file" name="proof_image" required>
<p style="color:red;">Rejected - Upload again</p>
<?php } else { ?>
<p style="color:lightgreen;">Approved</p>
<?php } ?>

<!-- ===== AADHAAR ===== -->
<p>Aadhaar:</p>
<img src="../<?php echo $data['aadhaar']; ?>">

<?php if($data['aadhaar_status']==2){ ?>
<input type="file" name="aadhaar" required>
<p style="color:red;">Rejected - Upload again</p>
<?php } else { ?>
<p style="color:lightgreen;">Approved</p>
<?php } ?>

<!-- ===== PAN ===== -->
<p>PAN Card:</p>
<img src="../<?php echo $data['pan']; ?>">

<?php if($data['pan_status']==2){ ?>
<input type="file" name="pan" required>
<p style="color:red;">Rejected - Upload again</p>
<?php } else { ?>
<p style="color:lightgreen;">Approved</p>
<?php } ?>

<button name="update">Update Documents</button>

</form>

</div>

</body>
</html>