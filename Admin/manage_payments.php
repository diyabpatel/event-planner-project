<?php
session_start();
include("../db.php");

/* UTF-8 */
header('Content-Type: text/html; charset=utf-8');

$q = mysqli_query($conn,"
SELECT p.*,u.college_name,b.payment_status 
FROM payments p
JOIN users u ON p.user_id=u.user_id
JOIN bookings b ON p.booking_id=b.booking_id
ORDER BY p.payment_id DESC
");

/* ARRANGE INTO COLUMNS */
$pending = [];
$approved = [];
$rejected = [];

while($row=mysqli_fetch_assoc($q)){
    if($row['payment_status']=="Advance Paid"){
        $approved[] = $row;
    }
    elseif($row['payment_status']=="Rejected"){
        $rejected[] = $row;
    }
    else{
        $pending[] = $row;
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
}

body{
font-family:'Segoe UI',system-ui;
background:#0f172a;
color:#e2e8f0;
padding:25px;
}

/* HEADER */
h2{
text-align:center;
margin-bottom:25px;
font-size:28px;
}

/* BOARD */
.board{
display:flex;
gap:20px;
justify-content:center;
flex-wrap:wrap;
}

/* COLUMN */
.column{
flex:1;
min-width:300px;
background:#1e293b;
border-radius:12px;
padding:15px;
}

.column h3{
margin-bottom:15px;
font-size:16px;
display:flex;
justify-content:space-between;
}

/* COUNT BADGE */
.count{
background:#334155;
padding:3px 10px;
border-radius:20px;
font-size:12px;
}

/* CARD */
.card{
background:#020617;
padding:15px;
border-radius:10px;
margin-bottom:12px;
transition:0.2s;
border-left:4px solid #3b82f6;
}

.card:hover{
transform:translateY(-3px);
}

/* DIFFERENT COLORS */
.pending-card{border-color:#facc15;}
.approved-card{border-color:#22c55e;}
.rejected-card{border-color:#ef4444;}

/* NAME */
.name{
font-weight:600;
margin-bottom:5px;
}

/* AMOUNT */
.amount{
font-size:16px;
font-weight:700;
color:#38bdf8;
margin-bottom:5px;
}

/* METHOD */
.method{
font-size:12px;
color:#94a3b8;
}

/* BUTTON */
.btn{
display:inline-block;
margin-top:8px;
padding:6px 12px;
font-size:12px;
border-radius:6px;
text-decoration:none;
background:#3b82f6;
color:white;
}

.btn:hover{
background:#2563eb;
}

</style>

</head>

<body>

<h2>💳 Payment Board</h2>

<div class="board">

<!-- PENDING -->
<div class="column">
<h3>Pending <span class="count"><?php echo count($pending); ?></span></h3>

<?php foreach($pending as $row){ ?>
<div class="card pending-card">
<div class="name"><?php echo htmlspecialchars($row['college_name']); ?></div>
<div class="amount">&#8377; <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>

</div>

<!-- APPROVED -->
<div class="column">
<h3>Confirmed <span class="count"><?php echo count($approved); ?></span></h3>

<?php foreach($approved as $row){ ?>
<div class="card approved-card">
<div class="name"><?php echo htmlspecialchars($row['college_name']); ?></div>
<div class="amount">&#8377; <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>

</div>

<!-- REJECTED -->
<div class="column">
<h3>Rejected <span class="count"><?php echo count($rejected); ?></span></h3>

<?php foreach($rejected as $row){ ?>
<div class="card rejected-card">
<div class="name"><?php echo htmlspecialchars($row['college_name']); ?></div>
<div class="amount">&#8377; <?php echo number_format($row['amount'],2); ?></div>
<div class="method"><?php echo htmlspecialchars($row['payment_method']); ?></div>
<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">View</a>
</div>
<?php } ?>

</div>

</div>

</body>
</html>