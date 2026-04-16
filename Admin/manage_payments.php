<?php
session_start();
include("../db.php");

header('Content-Type: text/html; charset=utf-8');

$q = mysqli_query($conn,"
SELECT 
    p.*, 
    u.college_name,
    e.event_name,
    e.image AS event_image
FROM payments p
JOIN users u ON p.user_id = u.user_id
JOIN bookings b ON p.booking_id = b.booking_id
JOIN events e ON b.event_id = e.event_id
ORDER BY p.payment_id DESC
");

if(!$q){
    die("SQL Error: " . mysqli_error($conn));
}

$pending = [];
$approved = [];
$rejected = [];

while($row=mysqli_fetch_assoc($q)){
$status = strtolower($row['payment_status']);

if(strpos($status,'pending') !== false){
    $pending[] = $row;
}
elseif(strpos($status,'reject') !== false){
    $rejected[] = $row;
}
else{
    $approved[] = $row;
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Payments</title>

<style>

/* IMPORT FONT */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

/* BODY */
body{
background:linear-gradient(135deg,#fdfbff,#f3f0ff);
}

/* MAIN */
.main-content{
margin-left:260px;
padding:30px;
animation:fadePage 0.6s ease;
}

@keyframes fadePage{
from{opacity:0; transform:translateY(20px);}
to{opacity:1; transform:translateY(0);}
}

/* TITLE */
h2{
text-align:center;
margin-bottom:20px;
font-size:26px;
font-weight:700;
color:#4c1d95;
}

/* FILTER BAR */
.filter-bar{
display:flex;
justify-content:center;
gap:12px;
margin-bottom:25px;
flex-wrap:wrap;
}

.filter-btn{
padding:8px 18px;
border:none;
border-radius:30px;
background:rgba(124,58,237,0.08);
color:#6d28d9;
font-size:13px;
cursor:pointer;
transition:0.3s;
}

.filter-btn:hover{
background:#7c3aed;
color:#fff;
transform:translateY(-2px);
box-shadow:0 8px 18px rgba(124,58,237,0.3);
}

.filter-btn.active{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
box-shadow:0 10px 25px rgba(124,58,237,0.4);
}

/* GRID FIX */
.tab{
display:none;
}

.tab.active{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); /* 🔥 FIXED */
gap:25px;
justify-content:center;
animation:fade 0.4s ease;
}

@keyframes fade{
from{opacity:0; transform:translateY(10px);}
to{opacity:1; transform:translateY(0);}
}

/* CARD */
.card{
width:100%;
max-width:300px;
min-height:340px;
background:rgba(255,255,255,0.85);
padding:15px;
border-radius:18px;
backdrop-filter:blur(12px);
box-shadow:0 10px 30px rgba(0,0,0,0.06);
transition:0.3s;
display:flex;
flex-direction:column;
gap:10px;
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 20px 45px rgba(124,58,237,0.2);
}

/* STATUS COLORS */
.pending-card::before,
.approved-card::before,
.rejected-card::before{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:4px;
}

.pending-card::before{background:#facc15;}
.approved-card::before{background:#22c55e;}
.rejected-card::before{background:#ef4444;}

/* 🔥 EVENT IMAGE (SQUARE FIX) */
.event-img{
width:100%;
aspect-ratio:1/1;
overflow:hidden;
border-radius:14px;
}

.event-img img{
width:100%;
height:100%;
object-fit:cover;
}

/* EVENT NAME */
.event-name{
font-size:15px;
font-weight:600;
color:#5b21b6;
margin-top:5px;
}

/* IMAGE GRID */
.img-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:6px;
}

.img-grid img{
width:100%;
height:70px;
object-fit:cover;
border-radius:10px;
transition:0.3s;
}

.img-grid img:hover{
transform:scale(1.08);
box-shadow:0 6px 15px rgba(0,0,0,0.15);
}

/* TEXT */
.name{
font-weight:600;
font-size:14px;
color:#2e1065;
}

.amount{
font-size:16px;
font-weight:700;
color:#6d28d9;
}

.method{
font-size:12px;
color:#6b7280;
}

/* BUTTON */
.btn{
display:inline-block;
margin-top:6px;
padding:6px 14px;
font-size:12px;
border-radius:20px;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
text-decoration:none;
transition:0.3s;
}

.btn:hover{
transform:scale(1.08);
box-shadow:0 8px 20px rgba(124,58,237,0.4);
}

/* MOBILE */
@media(max-width:768px){
.main-content{
margin-left:0;
padding:15px;
}

.tab.active{
grid-template-columns:1fr;
}

.card{
max-width:100%;
}
}
</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<h2>💳 Payment Board</h2>

<div class="filter-bar">
<button class="filter-btn active" onclick="showTab('pending',event)">Pending</button>
<button class="filter-btn" onclick="showTab('approved',event)">Approved</button>
<button class="filter-btn" onclick="showTab('rejected',event)">Rejected</button>
</div>

<!-- PENDING -->
<div class="tab active" id="pendingTab">
<?php foreach($pending as $row){ ?>
<div class="card pending-card">

<div class="event-img">
    <img src="/event-planner-project/uploads/images/events_images/<?php echo $row['event_image']; ?>" 
    onerror="this.src='https://via.placeholder.com/300x150'">
</div>

<div class="event-name">
    <?php echo htmlspecialchars($row['event_name']); ?>
</div>

<div class="img-grid">

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['proof_image']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['aadhaar']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['pan']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

</div>

<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>

</div>
<?php } ?>
</div>

<!-- APPROVED -->
<div class="tab" id="approvedTab">
<?php foreach($approved as $row){ ?>
<div class="card approved-card">

<div class="event-img">
    <img src="/event-planner-project/uploads/images/events_images/<?php echo $row['event_image']; ?>" 
    onerror="this.src='https://via.placeholder.com/300x150'">
</div>

<div class="event-name">
    <?php echo htmlspecialchars($row['event_name']); ?>
</div>

<div class="img-grid">

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['proof_image']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['aadhaar']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['pan']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

</div>

<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>

</div>
<?php } ?>
</div>

<!-- REJECTED -->
<div class="tab" id="rejectedTab">
<?php foreach($rejected as $row){ ?>
<div class="card rejected-card">

<div class="event-img">
    <img src="/event-planner-project/uploads/images/events_images/<?php echo $row['event_image']; ?>" 
    onerror="this.src='https://via.placeholder.com/300x150'">
</div>

<div class="event-name">
    <?php echo htmlspecialchars($row['event_name']); ?>
</div>

<div class="img-grid">

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['proof_image']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['aadhaar']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>">
<img src="/event-planner-project/<?php echo $row['pan']; ?>" 
onerror="this.src='https://via.placeholder.com/60'">
</a>

</div>

<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>

</div>
<?php } ?>
</div>

</div>

<script>
function showTab(type,e){

document.querySelectorAll('.tab').forEach(tab=>{
tab.classList.remove('active');
});

document.getElementById(type+'Tab').classList.add('active');

document.querySelectorAll('.filter-btn').forEach(btn=>{
btn.classList.remove('active');
});

e.target.classList.add('active');
}
</script>

</body>
</html>