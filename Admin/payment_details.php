<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);

/* 🔥 FETCH WITH EVENT NAME */
$row = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT p.*, b.event_id, e.event_name 
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN events e ON b.event_id = e.event_id
    WHERE p.payment_id = $id
"));

$booking_id = $row['booking_id'];
$event_name = $row['event_name'];
/* 🔥 FINAL ACTION */
if(isset($_GET['final'])){

if($_GET['final']=="confirm"){

    mysqli_query($conn,"UPDATE payments SET payment_status='Approved' WHERE payment_id=$id");

    $msg = "Your booking for the event \"$event_name\" has been successfully confirmed. Thank you for your payment.";

    mysqli_query($conn,"
        UPDATE bookings 
        SET notification='$msg', payment_status='Approved'
        WHERE booking_id=$booking_id
    ");

    header("Location: manage_payments.php");
    exit();
}

if($_GET['final']=="reject"){

    mysqli_query($conn,"UPDATE payments SET payment_status='Rejected' WHERE payment_id=$id");

    $msg = "Your payment for the event \"$event_name\" was not approved. Please re-upload valid payment details to proceed with your booking.";

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

/* IMAGE */
.viewer img{
max-width:80vw;
max-height:70vh;
border-radius:18px;
box-shadow:0 0 60px rgba(124,58,237,0.4);
}

/* NAV + CLOSE */
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

/* BUTTON */
.btn{
padding:10px 20px;
border:none;
border-radius:25px;
margin:6px;
cursor:pointer;
}

.approve{background:#22c55e;color:white;}
.reject{background:#ef4444;color:white;}

/* STATUS */
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

<!-- 🔥 NAV BACK -->
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
let finalStatus = "<?php echo $row['payment_status']; ?>";

/* 🔥 SET BASED ON DB */
let docStatus = ["Pending","Pending","Pending"];

if(finalStatus.toLowerCase() === "approved"){
docStatus = ["Approved","Approved","Approved"];
}
else if(finalStatus.toLowerCase() === "rejected"){
docStatus = ["Rejected","Rejected","Rejected"];
}

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

function updateStatus(action){

if(action.includes("A")){
docStatus[current] = "Approved";
}else{
docStatus[current] = "Rejected";
}

fetch(`payment_action.php?id=<?php echo $id ?>&action=${action}`)
.then(()=>{
show();
setTimeout(nextImg,400);
});
}

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

function finalDecision(){

let hasReject = docStatus.includes("Rejected");

if(hasReject){

Swal.fire({
title:"Reject Payment?",
text:"User will need to re-upload documents",
icon:"warning",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
window.location.href="?id=<?php echo $id ?>&final=reject";
}
});

}else{

Swal.fire({
title:"Confirm Payment?",
text:"All documents verified",
icon:"question",
showCancelButton:true
}).then(r=>{
if(r.isConfirmed){
window.location.href="?id=<?php echo $id ?>&final=confirm";
}
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