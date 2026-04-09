<?php
session_start();
include("../db.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id == 0){
    die("Invalid ID");
}

/* ACTIONS */
if(isset($_GET['action'])){
    $action = $_GET['action'];

    if($action=="proofA") mysqli_query($conn,"UPDATE payments SET proof_status=1 WHERE payment_id=$id");
    if($action=="proofR") mysqli_query($conn,"UPDATE payments SET proof_status=2 WHERE payment_id=$id");

    if($action=="aadhaarA") mysqli_query($conn,"UPDATE payments SET aadhaar_status=1 WHERE payment_id=$id");
    if($action=="aadhaarR") mysqli_query($conn,"UPDATE payments SET aadhaar_status=2 WHERE payment_id=$id");

    if($action=="panA") mysqli_query($conn,"UPDATE payments SET pan_status=1 WHERE payment_id=$id");
    if($action=="panR") mysqli_query($conn,"UPDATE payments SET pan_status=2 WHERE payment_id=$id");

    header("Location: payment_details.php?id=$id");
    exit();
}

/* FINAL */
if(isset($_GET['final'])){
    $type = $_GET['final'];

    $data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT booking_id FROM payments WHERE payment_id=$id"));
    $booking_id = $data['booking_id'];

    if($type=="confirm"){
        mysqli_query($conn,"UPDATE bookings SET payment_status='Approved' WHERE booking_id=$booking_id");
        mysqli_query($conn,"UPDATE payments SET payment_status='Approved' WHERE payment_id=$id");
    }

    header("Location: manage_payments.php");
    exit();
}

$row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM payments WHERE payment_id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Details</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

/* GLOBAL */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

/* BACKGROUND SAME AS GALLERY */
body{
background:linear-gradient(135deg,#0f172a,#020617);
color:white;
}

/* CONTAINER */
.container{
max-width:700px;
margin:auto;
padding:40px;
text-align:center;
}

/* CARD GLASS EFFECT */
.card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
border-radius:18px;
padding:20px;
backdrop-filter:blur(10px);
box-shadow:0 10px 30px rgba(0,0,0,0.4);
transition:0.3s;
}

.card:hover{
transform:translateY(-6px);
}

/* IMAGE */
.slider img{
width:100%;
height:260px;
object-fit:cover;
border-radius:12px;
transition:0.3s;
}

.slider img:hover{
transform:scale(1.03);
}

/* TITLE */
h3{
margin-top:15px;
font-size:20px;
}

/* BUTTONS */
.actions{
margin-top:15px;
}

.btn{
padding:8px 16px;
border:none;
border-radius:20px;
cursor:pointer;
margin:5px;
font-size:13px;
transition:0.3s;
}

.approve{ background:#22c55e; color:white; }
.reject{ background:#ef4444; color:white; }

.btn:hover{
transform:scale(1.08);
}

/* NAV */
.nav{
margin-top:15px;
display:flex;
justify-content:center;
gap:20px;
}

.nav button{
padding:10px 16px;
background:#6366f1;
border:none;
color:white;
border-radius:10px;
cursor:pointer;
font-size:16px;
transition:0.3s;
}

.nav button:hover{
transform:scale(1.1);
}

/* PROGRESS */
.progress{
margin-top:10px;
font-size:13px;
color:#cbd5f5;
}

</style>
</head>

<body>

<div class="container">

<div class="card">

<div class="slider">
<img id="sliderImg" src="../<?php echo $row['proof_image']; ?>">
</div>

<h3 id="docTitle">Payment Screenshot</h3>

<div class="progress" id="progress">1 / 3</div>

<div class="actions">
<button class="btn approve" onclick="approveDoc()">Approve</button>
<button class="btn reject" onclick="rejectDoc()">Reject</button>
</div>

<div class="nav">
<button onclick="prevImg()">⬅</button>
<button onclick="nextImg()">➡</button>
</div>

</div>

</div>

<script>

let current = 0;

const docs = [
    { img: "../<?php echo $row['proof_image']; ?>", title: "Payment Screenshot", type: "proof" },
    { img: "../<?php echo $row['aadhaar']; ?>", title: "Aadhaar", type: "aadhaar" },
    { img: "../<?php echo $row['pan']; ?>", title: "PAN Card", type: "pan" }
];

function showDoc(){
    document.getElementById("sliderImg").src = docs[current].img;
    document.getElementById("docTitle").innerText = docs[current].title;
    document.getElementById("progress").innerText = (current+1)+" / "+docs.length;
}

function nextImg(){
    if(current < docs.length - 1){
        current++;
        showDoc();
    } else {
        showFinalAlert();
    }
}

function prevImg(){
    if(current > 0){
        current--;
        showDoc();
    }
}

function approveDoc(){
    let type = docs[current].type;
    window.location.href = "?id=<?php echo $id ?>&action="+type+"A";
}

function rejectDoc(){
    let type = docs[current].type;
    window.location.href = "?id=<?php echo $id ?>&action="+type+"R";
}

function showFinalAlert(){
    Swal.fire({
        title: "Confirm Booking?",
        text: "Are you sure you want to confirm?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#22c55e",
        cancelButtonColor: "#ef4444",
        confirmButtonText: "Yes Confirm"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?id=<?php echo $id ?>&final=confirm";
        }
    });
}

</script>

</body>
</html>