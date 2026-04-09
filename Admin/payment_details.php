<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM payments WHERE payment_id=$id"));
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
background:rgba(0,0,0,0.95);
display:flex;
align-items:center;
justify-content:center;
height:100vh;
font-family:'Poppins',sans-serif;
}

/* VIEWER */
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
box-shadow:0 0 50px rgba(255,255,255,0.15);
}

/* 🔥 CROSS → SCREEN EDGE */
.close{
position:fixed;
top:20px;
right:30px;
font-size:32px;
color:white;
cursor:pointer;
z-index:9999;
background:rgba(0,0,0,0.4);
border-radius:50%;
padding:6px 12px;
}

/* 🔥 ARROWS → SCREEN EDGE */
.nav{
position:fixed;
top:50%;
transform:translateY(-50%);
font-size:35px;
color:white;
cursor:pointer;
background:rgba(0,0,0,0.4);
padding:12px;
border-radius:50%;
z-index:9999;
transition:0.3s;
}

.nav:hover{
background:rgba(0,0,0,0.7);
}

.prev{ left:20px; }   /* LEFT EDGE */
.next{ right:20px; }  /* RIGHT EDGE */

/* TITLE */
.title{
color:white;
margin-top:12px;
font-size:18px;
}

/* BUTTONS */
.actions{
margin-top:12px;
}

.btn{
padding:10px 18px;
border:none;
border-radius:25px;
margin:5px;
font-size:14px;
cursor:pointer;
}

.approve{ background:#22c55e; color:white; }
.reject{ background:#ef4444; color:white; }

</style>
</head>

<body>

<div class="viewer">

<!-- 🔥 EDGE CONTROLS -->
<div class="close" onclick="goBack()">✕</div>
<div class="nav prev" onclick="prevImg()">❮</div>
<div class="nav next" onclick="nextImg()">❯</div>

<img id="img">

<div class="title" id="title"></div>

<div class="actions">
<button class="btn approve" onclick="approveDoc()">Approve</button>
<button class="btn reject" onclick="rejectDoc()">Reject</button>
</div>

</div>

<script>

let current = 0;

const docs = [
{ img: "/event-planner-project/<?php echo $row['proof_image']; ?>", title:"Payment Screenshot", type:"proof"},
{ img: "/event-planner-project/<?php echo $row['aadhaar']; ?>", title:"Aadhaar", type:"aadhaar"},
{ img: "/event-planner-project/<?php echo $row['pan']; ?>", title:"PAN Card", type:"pan"}
];

function show(){
document.getElementById("img").src = docs[current].img;
document.getElementById("title").innerText = docs[current].title;
}

/* NEXT */
function nextImg(){
if(current < docs.length-1){
current++;
show();
}else{
finalConfirm();
}
}

/* PREV */
function prevImg(){
if(current > 0){
current--;
show();
}
}

/* AJAX */
function updateStatus(action){
fetch(`payment_action.php?id=<?php echo $id ?>&action=${action}`)
.then(res => res.text())
.then(()=>{
nextImg();
});
}

/* APPROVE */
function approveDoc(){
let type = docs[current].type;

Swal.fire({
title: "Approve this document?",
icon: "question",
showCancelButton: true,
confirmButtonColor: "#22c55e"
}).then((result)=>{
if(result.isConfirmed){
updateStatus(type+"A");
}
});
}

/* REJECT */
function rejectDoc(){
let type = docs[current].type;

Swal.fire({
title: "Reject this document?",
text: "User will need to re-upload",
icon: "warning",
showCancelButton: true,
confirmButtonColor: "#ef4444"
}).then((result)=>{
if(result.isConfirmed){
updateStatus(type+"R");
}
});
}

/* FINAL */
function finalConfirm(){
Swal.fire({
title:"Confirm Payment?",
text:"All documents verified?",
icon:"question",
showCancelButton:true,
confirmButtonColor:"#22c55e"
}).then((r)=>{
if(r.isConfirmed){
window.location.href="?id=<?php echo $id ?>&final=confirm";
}
});
}

/* CLOSE */
function goBack(){
window.location.href="manage_payments.php";
}

/* ESC CLOSE */
document.addEventListener("keydown",(e)=>{
if(e.key==="Escape"){
goBack();
}
});

show();

</script>

</body>
</html>