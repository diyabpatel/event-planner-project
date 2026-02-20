<?php
session_start();
include("../db.php");

// Protect page
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

/* FETCH BOOKINGS */
$query = mysqli_query($conn,"
SELECT b.*, 
       u.college_name, 
       e.event_name, 
       p.package_name
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN events e ON b.event_id = e.event_id
JOIN packages p ON b.package_id = p.package_id
ORDER BY b.booking_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Bookings</title>

<style>
*{
    box-sizing:border-box;
}
body{
    font-family:'Segoe UI', Arial, sans-serif;
    margin:0;
    background:
        radial-gradient(circle at top left,#dbeafe,transparent 40%),
        radial-gradient(circle at bottom right,#bfdbfe,transparent 40%),
        linear-gradient(135deg,#eef4ff,#f8fbff);
}

/* HEADER */
.header{
    background:rgba(30,64,175,0.85);
    backdrop-filter:blur(14px);
    color:#fff;
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    position:sticky;
    top:0;
    z-index:10;
}

.header h2{
    margin:0;
    font-size:24px;
    letter-spacing:0.5px;
}

.back-btn{
    background:linear-gradient(135deg,#ef4444,#dc2626);
    padding:10px 18px;
    border-radius:30px;
    text-decoration:none;
    color:white;
    font-size:14px;
    font-weight:600;
    box-shadow:0 6px 20px rgba(239,68,68,0.5);
    transition:0.3s;
}
.back-btn:hover{
    transform:translateY(-2px) scale(1.05);
}

/* CONTAINER */
.container{
    padding:30px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:rgba(255,255,255,0.65);
    backdrop-filter:blur(18px);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

th{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    padding:14px;
    font-size:14px;
    letter-spacing:0.4px;
}

td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid rgba(0,0,0,0.08);
    font-size:14px;
}

tr{
    transition:0.25s;
}
tr:hover{
    background:rgba(37,99,235,0.08);
}

/* AMOUNT STYLES */
.total{
    font-weight:700;
    color:#1e3a8a;
}

.advance{
    color:#16a34a;
    font-weight:700;
}

.remaining{
    color:#dc2626;
    font-weight:700;
}

/* STATUS BADGES */
.paid{
    background:rgba(22,163,74,0.15);
    color:#15803d;
    padding:6px 14px;
    border-radius:20px;
    font-weight:700;
}

.pending{
    background:rgba(234,88,12,0.15);
    color:#c2410c;
    padding:6px 14px;
    border-radius:20px;
    font-weight:700;
}
</style>

</head>
<body>

<div class="header">
    <h2>Manage Bookings</h2>
    <a href="AdminDashboard.php" class="back-btn">Back</a>
</div>

<div class="container">

<table>
<tr>
    <th>ID</th>
    <th>College</th>
    <th>Event</th>
    <th>Package</th>
    <th>Event Date</th>
    <th>Total</th>
    <th>Advance (25%)</th>
    <th>Remaining</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>
<tr>
    <td><?php echo $row['booking_id']; ?></td>
    <td><?php echo $row['college_name']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['event_date']; ?></td>

    <td class="total">₹ <?php echo $row['total_price']; ?></td>
    <td class="advance">₹ <?php echo $row['advance_paid']; ?></td>
    <td class="remaining">₹ <?php echo $row['remaining_amount']; ?></td>

    <td>
        <?php 
        if($row['remaining_amount'] == 0){
            echo "<span class='paid'>Paid</span>";
        } else {
            echo "<span class='pending'>Pending</span>";
        }
        ?>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>