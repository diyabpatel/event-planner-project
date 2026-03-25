<?php
session_start();
include("../db.php");

$q = mysqli_query($conn,"
SELECT p.*,u.college_name,b.payment_status 
FROM payments p
JOIN users u ON p.user_id=u.user_id
JOIN bookings b ON p.booking_id=b.booking_id
ORDER BY p.payment_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Payments</title>

<style>

body{
font-family:Segoe UI;
background:#0f172a;
color:white;
padding:30px;
}

/* CARD LIST */
.card{
background:#1e293b;
padding:20px;
margin-bottom:20px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
transition:0.3s;
}

.card:hover{
transform:translateY(-3px);
}

/* STATUS */
.status{
font-weight:bold;
}

.approved{color:#22c55e;}
.pending{color:#facc15;}
.rejected{color:#ef4444;}

/* BUTTON */
.btn{
padding:8px 14px;
background:#3b82f6;
color:white;
text-decoration:none;
border-radius:6px;
}

</style>

</head>

<body>

<h2>Manage Payments</h2>

<?php while($row=mysqli_fetch_assoc($q)){ ?>

<div class="card">

<div>
<b><?php echo $row['college_name']; ?></b><br>
Amount: &#8377; <?php echo number_format($row['amount'],2); ?><br>

<?php
if($row['payment_status']=="Advance Paid"){
echo "<span class='status approved'>Confirmed</span>";
}
else if($row['payment_status']=="Rejected"){
echo "<span class='status rejected'>Rejected</span>";
}
else{
echo "<span class='status pending'>Pending</span>";
}
?>

</div>

<a href="payment_details.php?id=<?php echo $row['payment_id']; ?>" class="btn">
View Details
</a>

</div>

<?php } ?>

</body>
</html>