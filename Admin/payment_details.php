<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);

/* ✅ ACTION HANDLE (NEW ADDITION - IMPORTANT FIX) */
if(isset($_GET['action'])){
    $action = $_GET['action'];

    if($action == "proofA"){
        mysqli_query($conn,"UPDATE payments SET proof_status=1 WHERE payment_id=$id");
    }
    elseif($action == "proofR"){
        mysqli_query($conn,"UPDATE payments SET proof_status=2 WHERE payment_id=$id");
    }
    elseif($action == "aadhaarA"){
        mysqli_query($conn,"UPDATE payments SET aadhaar_status=1 WHERE payment_id=$id");
    }
    elseif($action == "aadhaarR"){
        mysqli_query($conn,"UPDATE payments SET aadhaar_status=2 WHERE payment_id=$id");
    }
    elseif($action == "panA"){
        mysqli_query($conn,"UPDATE payments SET pan_status=1 WHERE payment_id=$id");
    }
    elseif($action == "panR"){
        mysqli_query($conn,"UPDATE payments SET pan_status=2 WHERE payment_id=$id");
    }

    echo "done";
    exit();
}

/* 🔥 FETCH DATA */
$row = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT p.*, b.event_id, e.event_name 
FROM payments p
JOIN bookings b ON p.booking_id = b.booking_id
JOIN events e ON b.event_id = e.event_id
WHERE p.payment_id = $id
"));

$booking_id = $row['booking_id'];
$event_name = $row['event_name'];

/* 🔥 FINAL ACTION (FIXED LOGIC) */
if(isset($_GET['final'])){

    $check = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT proof_status,aadhaar_status,pan_status 
        FROM payments WHERE payment_id=$id
    "));

    $statuses = [
        $check['proof_status'],
        $check['aadhaar_status'],
        $check['pan_status']
    ];

    $allApproved = true;
    $hasReject = false;

    foreach($statuses as $s){
        if($s != 1) $allApproved = false;
        if($s == 2) $hasReject = true;
    }

    if($_GET['final']=="confirm" && $allApproved){

        mysqli_query($conn,"UPDATE payments SET payment_status='Approved' WHERE payment_id=$id");

        $msg = "Your booking for the event \"$event_name\" has been successfully confirmed.";

        mysqli_query($conn,"
            UPDATE bookings 
            SET notification='$msg', payment_status='Approved'
            WHERE booking_id=$booking_id
        ");

        header("Location: manage_payments.php");
        exit();
    }

    if($_GET['final']=="reject" && $hasReject){

        mysqli_query($conn,"UPDATE payments SET payment_status='Rejected' WHERE payment_id=$id");

        $msg = "Your payment was not approved. Please re-upload only rejected documents.";

        mysqli_query($conn,"
            UPDATE bookings 
            SET notification='$msg', payment_status='Rejected'
            WHERE booking_id=$booking_id
        ");

        header("Location: manage_payments.php");
        exit();
    }
}

$status = strtolower($row['payment_status']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Details</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

/* 🔥 SAME ORIGINAL STYLING (RESTORED) */
body{
margin:0;
background:linear-gradient(135deg,#0f0c29,#1e1b4b,#312e81);
display:flex;
align-items:center;
justify-content:center;
height:100vh;
font-family:'Poppins',sans-serif;
}

.viewer{
display:flex;
flex-direction:column;
align-items:center;
}

.viewer img{
max-width:80vw;
max-height:70vh;
border-radius:18px;
box-shadow:0 0 60px rgba(124,58,237,0.4);
}

.close{
position:fixed;
top:20px;
right:30px;
font-size:30px;
color:white;
cursor:pointer;
background:rgba(124,58,237,0.3);
border-radius:50%;
padding:6px 12px;
}

.nav{
position:fixed;
top:50%;
transform:translateY(-50%);
font-size:35px;
color:white;
cursor:pointer;
background:rgba(124,58,237,0.25);
padding:12px;
border-radius:50%;
}

.prev{ left:20px; }
.next{ right:20px; }

.title{
color:#e0d4ff;
margin-top:14px;
}

.btn{
padding:10px 20px;
border:none;
border-radius:25px;
margin:6px;
cursor:pointer;
}

.approve{background:#22c55e;color:white;}
.reject{background:#ef4444;color:white;}

.status-text{
margin-top:10px;
font-weight:600;
font-size:15px;
}

.approved-text{color:#22c55e;}
.rejected-text{color:#ef4444;}
.pending-text{color:#c4b5fd;}

</style>
</head>

<body>

<div class="viewer">

<div class="close" onclick="goBack()">✕</div>
<div class="nav prev" onclick="prevImg()">❮</div>
<div class="nav next" onclick="nextImg()">❯</div>

<img id="img">
<div class="title" id="title"></div>

<div id="docStatus" class="status-text pending-text">Pending</div>

<?php if(strpos($status,'pending') !== false){ ?>
<div id="actions">
<button class="btn approve" onclick="approveDoc()">Approve</button>
<button class="btn reject" onclick="rejectDoc()">Reject</button>
</div>
<?php } ?>

</div>

<script>

let current = 0;

/* ✅ FIXED: REAL STATUS LOAD */
let docStatus = [
<?php echo (int)$row['proof_status']; ?>,
<?php echo (int)$row['aadhaar_status']; ?>,
<?php echo (int)$row['pan_status']; ?>
];

docStatus = docStatus.map(s=>{
    if(s==1) return "Approved";
    if(s==2) return "Rejected";
    return "Pending";
});

const docs = [
{ img: "/event-planner-project/<?php echo $row['proof_image']; ?>", title:"Payment Screenshot", type:"proof"},
{ img: "/event-planner-project/<?php echo $row['aadhaar']; ?>", title:"Aadhaar", type:"aadhaar"},
{ img: "/event-planner-project/<?php echo $row['pan']; ?>", title:"PAN Card", type:"pan"}
];

function show(){
document.getElementById("img").src = docs[current].img;
document.getElementById("title").innerText = docs[current].title;

let el = document.getElementById("docStatus");
let statusText = docStatus[current];

el.innerText = statusText;
el.className = "status-text " + 
(statusText==="Approved" ? "approved-text" :
statusText==="Rejected" ? "rejected-text" : "pending-text");

if(statusText !== "Pending"){
document.getElementById("actions").style.display="none";
}else{
document.getElementById("actions").style.display="block";
}
}

function nextImg(){
if(current < docs.length-1){
current++;
show();
}else{
finalDecision();
}
}

function prevImg(){
if(current > 0){
current--;
show();
}
}

/* ✅ FIXED BACKEND CALL */
function updateStatus(action){

if(action.includes("A")){
docStatus[current] = "Approved";
}else{
docStatus[current] = "Rejected";
}

fetch(`payment_details.php?id=<?php echo $id ?>&action=${action}`)
.then(()=>{
show();
setTimeout(nextImg,400);
});
}

/* 🔥 SAME SWEET ALERTS RESTORED */
function approveDoc(){
let type = docs[current].type;

Swal.fire({
title:"Approve this document?",
icon:"question",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
updateStatus(type+"A");
}
});
}

function rejectDoc(){
let type = docs[current].type;

Swal.fire({
title:"Reject this document?",
text:"User will need to re-upload",
icon:"warning",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
updateStatus(type+"R");
}
});
}

/* ✅ FIXED FINAL LOGIC */
function finalDecision(){

let allApproved = docStatus.every(s => s==="Approved");
let hasReject = docStatus.includes("Rejected");

if(allApproved){

Swal.fire({
title:"Confirm Payment?",
icon:"question",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
window.location.href="?id=<?php echo $id ?>&final=confirm";
}
});

}
else if(hasReject){

Swal.fire({
title:"Reject Payment?",
text:"User will re-upload only rejected documents",
icon:"warning",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
window.location.href="?id=<?php echo $id ?>&final=reject";
}
});

}
else{

Swal.fire({
title:"Pending Documents",
text:"Please verify all documents",
icon:"info"
});

}
}

function goBack(){
window.location.href="manage_payments.php";
}

show();

</script>

</body>
</html>