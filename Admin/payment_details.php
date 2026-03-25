<?php
session_start();
include("../db.php");

$id = $_GET['id'];

/* ===== HANDLE ACTIONS ===== */

if(isset($_GET['action'])){
    $action=$_GET['action'];

    if($action=="proofA") mysqli_query($conn,"UPDATE payments SET proof_status=1 WHERE payment_id=$id");
    if($action=="proofR") mysqli_query($conn,"UPDATE payments SET proof_status=2 WHERE payment_id=$id");

    if($action=="aadhaarA") mysqli_query($conn,"UPDATE payments SET aadhaar_status=1 WHERE payment_id=$id");
    if($action=="aadhaarR") mysqli_query($conn,"UPDATE payments SET aadhaar_status=2 WHERE payment_id=$id");

    if($action=="panA") mysqli_query($conn,"UPDATE payments SET pan_status=1 WHERE payment_id=$id");
    if($action=="panR") mysqli_query($conn,"UPDATE payments SET pan_status=2 WHERE payment_id=$id");

    header("Location: payment_details.php?id=$id");
    exit();
}

/* ===== FINAL ACTION ===== */

if(isset($_GET['final'])){
    $type=$_GET['final'];

    $data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT booking_id FROM payments WHERE payment_id=$id"));

    if($type=="confirm"){
        mysqli_query($conn,"
        UPDATE bookings SET 
        payment_status='Advance Paid',
        notification='Your booking is confirmed',
        is_read=0
        WHERE booking_id=".$data['booking_id']);
    }

    if($type=="reject"){
        mysqli_query($conn,"
        UPDATE bookings SET 
        payment_status='Rejected',
        notification='Some documents were rejected. Please re-upload.',
        is_read=0
        WHERE booking_id=".$data['booking_id']);
    }

    header("Location: payment_details.php?id=$id&final_done=1");
    exit();
}

/* ===== FETCH ===== */

$row=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT p.*,u.college_name,b.payment_status 
FROM payments p
JOIN users u ON p.user_id=u.user_id
JOIN bookings b ON p.booking_id=b.booking_id
WHERE p.payment_id=$id
"));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Details</title>

<style>
body{
    font-family:Segoe UI;
    background:#0f172a;
    color:white;
    padding:30px;
}

.container{
    max-width:900px;
    margin:auto;
}

.card{
    background:#1e293b;
    padding:25px;
    border-radius:12px;
    margin-bottom:20px;
    text-align:center;
}

img{
    width:100%;
    max-width:400px;
    border-radius:10px;
    margin-top:10px;
}

.btn{
    padding:8px 14px;
    margin:5px;
    background:#3b82f6;
    color:white;
    text-decoration:none;
    border-radius:6px;
}

.reject{background:#ef4444;}
.final{background:#16a34a;}
.warn{background:#f59e0b;}

.status{
    margin-top:10px;
    font-weight:bold;
    font-size:16px;
}

.approved{color:#22c55e;}
.rejected{color:#ef4444;}
</style>

</head>

<body>

<div class="container">

<h2><?php echo $row['college_name']; ?></h2>

<!-- SCREENSHOT -->
<div class="card">
<h3>Payment Screenshot</h3>
<img src="../<?php echo $row['proof_image']; ?>">

<?php
if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){
    if($row['proof_status']==0){
?>
<a href="?id=<?php echo $id ?>&action=proofA" class="btn">Approve</a>
<a href="?id=<?php echo $id ?>&action=proofR" class="btn reject">Reject</a>
<?php
    } else if($row['proof_status']==1){
        echo "<div class='status approved'>&#10004; Approved</div>";
    } else{
        echo "<div class='status rejected'>&#10008; Rejected</div>";
    }
}else{
    echo ($row['proof_status']==1) 
    ? "<div class='status approved'>&#10004; Approved</div>" 
    : "<div class='status rejected'>&#10008; Rejected</div>";
}
?>
</div>

<!-- AADHAAR -->
<div class="card">
<h3>Aadhaar</h3>
<img src="../<?php echo $row['aadhaar']; ?>">

<?php
if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){
    if($row['aadhaar_status']==0){
?>
<a href="?id=<?php echo $id ?>&action=aadhaarA" class="btn">Approve</a>
<a href="?id=<?php echo $id ?>&action=aadhaarR" class="btn reject">Reject</a>
<?php
    } else if($row['aadhaar_status']==1){
        echo "<div class='status approved'>&#10004; Approved</div>";
    } else{
        echo "<div class='status rejected'>&#10008; Rejected</div>";
    }
}else{
    echo ($row['aadhaar_status']==1) 
    ? "<div class='status approved'>&#10004; Approved</div>" 
    : "<div class='status rejected'>&#10008; Rejected</div>";
}
?>
</div>

<!-- PAN -->
<div class="card">
<h3>PAN</h3>
<img src="../<?php echo $row['pan']; ?>">

<?php
if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){
    if($row['pan_status']==0){
?>
<a href="?id=<?php echo $id ?>&action=panA" class="btn">Approve</a>
<a href="?id=<?php echo $id ?>&action=panR" class="btn reject">Reject</a>
<?php
    } else if($row['pan_status']==1){
        echo "<div class='status approved'>&#10004; Approved</div>";
    } else{
        echo "<div class='status rejected'>&#10008; Rejected</div>";
    }
}else{
    echo ($row['pan_status']==1) 
    ? "<div class='status approved'>&#10004; Approved</div>" 
    : "<div class='status rejected'>&#10008; Rejected</div>";
}
?>
</div>

<hr>

<?php
if($row['payment_status'] == 'Advance Paid'){
    echo "<div class='status approved'>&#10004; Booking Confirmed</div>";
}
else if($row['payment_status'] == 'Rejected'){
    echo "<div class='status rejected'>&#10008; Re-upload Requested</div>";
}
else{
    if(
        $row['proof_status']!=0 &&
        $row['aadhaar_status']!=0 &&
        $row['pan_status']!=0
    ){
        if(
            $row['proof_status']==1 &&
            $row['aadhaar_status']==1 &&
            $row['pan_status']==1
        ){
?>
<a href="?id=<?php echo $id ?>&final=confirm" class="btn final">
Confirm Booking
</a>
<?php
        } else{
?>
<a href="?id=<?php echo $id ?>&final=reject" class="btn warn">
Request Re-upload
</a>
<?php
        }
    }
}
?>

</div>

<!-- ✅ SCROLL FIX SCRIPT -->
<script>
// Save scroll before reload
window.addEventListener("beforeunload", function () {
    localStorage.setItem("scrollPosition", window.scrollY);
});

// Restore scroll after reload
window.addEventListener("load", function () {
    const scrollPos = localStorage.getItem("scrollPosition");
    if (scrollPos !== null) {
        window.scrollTo(0, parseInt(scrollPos));
    }
});

// Clear scroll if final action done
if(window.location.href.includes("final_done")){
    localStorage.removeItem("scrollPosition");
}
</script>

</body>
</html>