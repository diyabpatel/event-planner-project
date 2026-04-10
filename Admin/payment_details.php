<?php
session_start();
include("../db.php");

$id = intval($_GET['id']);
$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM payments WHERE payment_id=$id"));

$status = strtolower($row['payment_status']); // 🔥 status check
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
box-shadow:0 0 60px rgba(124,58,237,0.4);
transition:0.3s;
}

.viewer img:hover{
transform:scale(1.02);
}

/* CLOSE */
.close{
position:fixed;
top:20px;
right:30px;
font-size:30px;
color:white;
cursor:pointer;
z-index:9999;
background:rgba(124,58,237,0.3);
border-radius:50%;
padding:6px 12px;
}

/* NAV */
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
z-index:9999;
transition:0.3s;
}

.nav:hover{
background:#7c3aed;
}

.prev{ left:20px; }
.next{ right:20px; }

/* TITLE */
.title{
color:#e0d4ff;
margin-top:14px;
font-size:20px;
font-weight:500;
}

/* ACTIONS */
.actions{
margin-top:14px;
}

/* BUTTON */
.btn{
padding:10px 20px;
border:none;
border-radius:25px;
margin:6px;
font-size:14px;
cursor:pointer;
transition:0.3s;
}

.approve{
background:linear-gradient(135deg,#22c55e,#16a34a);
color:white;
}

.reject{
background:linear-gradient(135deg,#ef4444,#dc2626);
color:white;
}

.btn:hover{
transform:scale(1.08);
box-shadow:0 8px 20px rgba(0,0,0,0.3);
}

/* STATUS BADGE */
.status-badge{
margin-top:12px;
padding:6px 14px;
border-radius:20px;
font-size:13px;
font-weight:500;
}

.approved-badge{
background:#22c55e;
color:white;
}

.rejected-badge{
background:#ef4444;
color:white;
}

</style>
</head>

<body>

<div class="viewer">

<div class="close" onclick="goBack()">✕</div>
<div class="nav prev" onclick="prevImg()">❮</div>
<div class="nav next" onclick="nextImg()">❯</div>

<img id="img">

<div class="title" id="title"></div>

<?php if(strpos($status,'pending') !== false){ ?>
<!-- ✅ ONLY FOR PENDING -->
<div class="actions">
<button class="btn approve" onclick="approveDoc()">Approve</button>
<button class="btn reject" onclick="rejectDoc()">Reject</button>
</div>
<?php } else { ?>
<!-- ❌ FOR APPROVED / REJECTED -->
<div class="status-badge <?php echo strpos($status,'reject')!==false ? 'rejected-badge':'approved-badge'; ?>">
<?php echo ucfirst($row['payment_status']); ?>
</div>
<?php } ?>

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

function nextImg(){
if(current < docs.length-1){
current++;
show();
}else{
<?php if(strpos($status,'pending') !== false){ ?>
finalConfirm(); // 🔥 only pending ma final confirm
<?php } ?>
}
}

function prevImg(){
if(current > 0){
current--;
show();
}
}

function updateStatus(action){
fetch(`payment_action.php?id=<?php echo $id ?>&action=${action}`)
.then(res => res.text())
.then(()=>{
nextImg();
});
}

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

function goBack(){
window.location.href="manage_payments.php";
}

document.addEventListener("keydown",(e)=>{
if(e.key==="Escape"){
goBack();
}
});

show();

</script>

</body>
</html>