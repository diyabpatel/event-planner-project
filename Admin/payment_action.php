<?php
include("../db.php");

$id = intval($_GET['id']);
$action = $_GET['action'];

/* UPDATE INDIVIDUAL */
if($action=="proofA") mysqli_query($conn,"UPDATE payments SET proof_status=1 WHERE payment_id=$id");
if($action=="proofR") mysqli_query($conn,"UPDATE payments SET proof_status=2 WHERE payment_id=$id");

if($action=="aadhaarA") mysqli_query($conn,"UPDATE payments SET aadhaar_status=1 WHERE payment_id=$id");
if($action=="aadhaarR") mysqli_query($conn,"UPDATE payments SET aadhaar_status=2 WHERE payment_id=$id");

if($action=="panA") mysqli_query($conn,"UPDATE payments SET pan_status=1 WHERE payment_id=$id");
if($action=="panR") mysqli_query($conn,"UPDATE payments SET pan_status=2 WHERE payment_id=$id");

/* 🔥 CHECK ALL APPROVED */
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM payments WHERE payment_id=$id"));

if($row['proof_status']==1 && $row['aadhaar_status']==1 && $row['pan_status']==1){

    mysqli_query($conn,"UPDATE payments SET payment_status='Approved' WHERE payment_id=$id");

    /* OPTIONAL booking update */
    mysqli_query($conn,"UPDATE bookings SET payment_status='Approved' WHERE booking_id=".$row['booking_id']);
}

/* 🔥 IF ANY REJECTED */
if($row['proof_status']==2 || $row['aadhaar_status']==2 || $row['pan_status']==2){

    mysqli_query($conn,"UPDATE payments SET payment_status='Rejected' WHERE payment_id=$id");
}

echo "done";