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
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

.main-content{
margin-left:260px;
padding:30px;
}

h2{
text-align:center;
margin-bottom:25px;
font-size:26px;
color:#5b21b6;
font-weight:600;
}

.board{
display:flex;
gap:25px;
justify-content:center;
flex-wrap:wrap;
}

.column{
flex:1;
min-width:300px;
background:#ede9fe;
border-radius:18px;
padding:18px;
box-shadow:0 12px 30px rgba(91,33,182,0.15);
}

.column h3{
margin-bottom:15px;
font-size:15px;
display:flex;
justify-content:space-between;
color:#5b21b6;
font-weight:600;
}

.count{
background:#7c3aed;
padding:5px 12px;
border-radius:20px;
font-size:12px;
color:white;
font-weight:600;
}

.card{
background:white;
padding:16px;
border-radius:14px;
margin-bottom:14px;
border-left:6px solid #7c3aed;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.pending-card{border-color:#facc15;}
.approved-card{border-color:#22c55e;}
.rejected-card{border-color:#ef4444;}

.name{
font-weight:600;
margin-bottom:6px;
color:#1f2937;
font-size:15px;
}

.amount{
font-size:17px;
font-weight:700;
color:#5b21b6;
margin-bottom:5px;
}

.method{
font-size:12px;
color:#6b7280;
margin-bottom:5px;
}

.btn{
display:inline-block;
margin-top:8px;
padding:7px 14px;
font-size:12px;
border-radius:20px;
text-decoration:none;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
font-weight:500;
}
</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<h2>💳 Payment Board</h2>

<div class="board">

<div class="column">
<h3>Pending <span class="count"><?php echo count($pending); ?></span></h3>

<?php foreach($pending as $row){ ?>
<div class="card pending-card">
<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>
</div>

<div class="column">
<h3>Confirmed <span class="count"><?php echo count($approved); ?></span></h3>

<?php foreach($approved as $row){ ?>
<div class="card approved-card">
<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>
</div>

<div class="column">
<h3>Rejected <span class="count"><?php echo count($rejected); ?></span></h3>

<?php foreach($rejected as $row){ ?>
<div class="card rejected-card">
<div class="name"><?php echo htmlspecialchars($row['payer_name']); ?></div>
<div class="amount">₹ <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>
</div>

</div>
</div>
</body>
</html>