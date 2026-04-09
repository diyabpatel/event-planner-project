<?php
session_start();
include("../db.php");

header('Content-Type: text/html; charset=utf-8');

$q = mysqli_query($conn,"
SELECT p.*,u.college_name
FROM payments p
JOIN users u ON p.user_id=u.user_id
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
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

body{
background:#f9f7ff;
}

/* MAIN */
.main-content{
margin-left:260px;
padding:30px;
}

/* TITLE */
h2{
text-align:center;
margin-bottom:15px;
font-size:22px;
color:#5b21b6;
font-weight:600;
}

/* FILTER */
.filter-bar{
display:flex;
justify-content:center;
gap:10px;
margin-bottom:20px;
flex-wrap:wrap;
}

.filter-btn{
padding:6px 14px;
border:none;
border-radius:20px;
background:#ede9fe;
color:#5b21b6;
font-size:12px;
cursor:pointer;
}

.filter-btn.active{
background:#7c3aed;
color:white;
}

/* GRID */
.tab{
display:none;
}

.tab.active{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
gap:15px;
animation:fade 0.3s ease;
}

@keyframes fade{
from{opacity:0; transform:translateY(10px);}
to{opacity:1; transform:translateY(0);}
}

/* CARD */
.card{
background:white;
padding:12px;
border-radius:10px;
border-left:4px solid #7c3aed;
font-size:13px;
transition:0.2s;
}

.card:hover{
transform:translateY(-4px);
box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

/* STATUS */
.pending-card{border-color:#facc15;}
.approved-card{border-color:#22c55e;}
.rejected-card{border-color:#ef4444;}

/* IMAGE GRID 🔥 */
.img-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:5px;
margin-bottom:8px;
}

.img-grid img{
width:100%;
height:60px;
object-fit:cover;
border-radius:6px;
cursor:pointer;
transition:0.3s;
}

.img-grid img:hover{
transform:scale(1.05);
}

/* TEXT */
.name{
font-weight:600;
font-size:13px;
}

.amount{
font-size:14px;
font-weight:600;
color:#5b21b6;
}

.method{
font-size:11px;
color:#6b7280;
}

/* BUTTON */
.btn{
display:inline-block;
margin-top:6px;
padding:4px 10px;
font-size:11px;
border-radius:15px;
background:#7c3aed;
color:white;
text-decoration:none;
transition:0.3s;
}

.btn:hover{
background:#5b21b6;
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