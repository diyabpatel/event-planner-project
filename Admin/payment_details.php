<?php
session_start();
include("../db.php");

/* ✅ SAFE ID */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id == 0){
    die("Invalid or missing ID");
}

/* ===== HANDLE ACTIONS ===== */

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

/* ===== FINAL ACTION ===== */

if(isset($_GET['final'])){
    $type = $_GET['final'];

    $result = mysqli_query($conn,"SELECT booking_id FROM payments WHERE payment_id=$id");

    if(!$result){
        die("Query Error: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($result);

    if(!$data){
        die("No booking found");
    }

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

$query = mysqli_query($conn,"
SELECT p.*,u.college_name,b.payment_status 
FROM payments p
JOIN users u ON p.user_id=u.user_id
JOIN bookings b ON p.booking_id=b.booking_id
WHERE p.payment_id=$id
");

if(!$query){
    die("Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($query);

if(!$row){
    die("No data found");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Details</title>

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(135deg,#0f172a,#020617);
color:#e5e7eb;
}

/* CONTAINER */
.container{
max-width:1100px;
margin:auto;
padding:30px;
}

/* HEADER */
.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.header h2{
font-size:24px;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
gap:20px;
}

/* CARD */
.card{
background:rgba(255,255,255,0.05);
border:1px solid rgba(255,255,255,0.1);
border-radius:16px;
padding:20px;
backdrop-filter:blur(10px);
transition:0.3s;
}

.card:hover{
transform:translateY(-5px);
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

/* IMAGE */
.preview{
width:100%;
height:220px;
border-radius:12px;
overflow:hidden;
margin-top:10px;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
cursor:pointer;
}

/* BUTTONS */
.actions{
margin-top:15px;
display:flex;
gap:10px;
}

.btn{
padding:8px 14px;
border-radius:20px;
text-decoration:none;
font-size:13px;
font-weight:500;
transition:0.3s;
}

.approve{ background:#22c55e; color:white; }
.reject{ background:#ef4444; color:white; }
.final{ background:#6366f1; color:white; }
.warn{ background:#f59e0b; color:white; }

.btn:hover{
transform:scale(1.05);
}

/* STATUS */
.status{
margin-top:12px;
font-size:14px;
font-weight:600;
}

.approved{color:#22c55e;}
.rejected{color:#ef4444;}

/* FINAL */
.final-box{
margin-top:30px;
text-align:center;
}

/* MODAL */
.modal{
display:none;
position:fixed;
z-index:999;
padding-top:50px;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.9);
}

.modal-content{
margin:auto;
display:block;
max-width:90%;
max-height:85%;
border-radius:10px;
}

.close{
position:absolute;
top:20px;
right:40px;
color:white;
font-size:35px;
cursor:pointer;
}

</style>

</head>

<body>



<div class="container">

<div class="header">
<h2><?php echo $row['college_name']; ?></h2>
</div>

<div class="grid">

<!-- PAYMENT -->
<div class="card">
<h3>Payment Screenshot</h3>
<div class="preview">
<img src="../<?php echo $row['proof_image']; ?>" onclick="openImage(this.src)">
</div>

<?php if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){ ?>
<div class="actions">
<a href="?id=<?php echo $id ?>&action=proofA" class="btn approve">Approve</a>
<a href="?id=<?php echo $id ?>&action=proofR" class="btn reject">Reject</a>
</div>
<?php } ?>

<div class="status 
<?php 
echo ($row['proof_status']==1 ? 'approved' : ($row['proof_status']==2 ? 'rejected' : 'pending')); 
?>">

<?php 
if($row['proof_status']==1){
    echo "✔ Approved";
}
elseif($row['proof_status']==2){
    echo "✖ Rejected";
}
else{
    echo "⏳ Pending";
}
?>
</div>

</div>

<!-- AADHAAR -->
<div class="card">
<h3>Aadhaar</h3>
<div class="preview">
<img src="../<?php echo $row['aadhaar']; ?>" onclick="openImage(this.src)">
</div>

<?php if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){ ?>
<div class="actions">
<a href="?id=<?php echo $id ?>&action=aadhaarA" class="btn approve">Approve</a>
<a href="?id=<?php echo $id ?>&action=aadhaarR" class="btn reject">Reject</a>
</div>
<?php } ?>

<div class="status 
<?php 
echo ($row['aadhaar_status']==1 ? 'approved' : ($row['aadhaar_status']==2 ? 'rejected' : 'pending')); 
?>">

<?php 
if($row['aadhaar_status']==1){
    echo "✔ Approved";
}
elseif($row['aadhaar_status']==2){
    echo "✖ Rejected";
}
else{
    echo "⏳ Pending";
}
?>
</div>
</div>

<!-- PAN -->
<div class="card">
<h3>PAN Card</h3>
<div class="preview">
<img src="../<?php echo $row['pan']; ?>" onclick="openImage(this.src)">
</div>

<?php if($row['payment_status']!='Advance Paid' && $row['payment_status']!='Rejected'){ ?>
<div class="actions">
<a href="?id=<?php echo $id ?>&action=panA" class="btn approve">Approve</a>
<a href="?id=<?php echo $id ?>&action=panR" class="btn reject">Reject</a>
</div>
<?php } ?>

<div class="status 
<?php 
echo ($row['pan_status']==1 ? 'approved' : ($row['pan_status']==2 ? 'rejected' : 'pending')); 
?>">

<?php 
if($row['pan_status']==1){
    echo "✔ Approved";
}
elseif($row['pan_status']==2){
    echo "✖ Rejected";
}
else{
    echo "⏳ Pending";
}
?>
</div>
</div>

</div>

<!-- FINAL -->
<div class="final-box">

<?php
if($row['payment_status'] == 'Advance Paid'){
    echo "<div class='status approved'>✔ Booking Confirmed</div>";
}
else if($row['payment_status'] == 'Rejected'){
    echo "<div class='status rejected'>✖ Re-upload Requested</div>";
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
            echo "<a href='?id=$id&final=confirm' class='btn final'>Confirm Booking</a>";
        } else{
            echo "<a href='?id=$id&final=reject' class='btn warn'>Request Re-upload</a>";
        }
    }
}
?>

</div>

</div>

<!-- IMAGE MODAL -->
<div id="imageModal" class="modal" onclick="closeImage()">
<span class="close">&times;</span>
<img class="modal-content" id="modalImg">
</div>

<script>
function openImage(src){
document.getElementById("imageModal").style.display="block";
document.getElementById("modalImg").src=src;
}

function closeImage(){
document.getElementById("imageModal").style.display="none";
}
</script>

</body>
</html>