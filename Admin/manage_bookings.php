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
body{
    font-family:'Segoe UI', Arial;
    margin:0;
    background:#f4f7fb;
}

/* HEADER */
.header{
    background:#1e40af;
    color:#fff;
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{
    margin:0;
}

.back-btn{
    background:#ef4444;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:14px;
}

/* CONTAINER */
.container{
    padding:30px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

th{
    background:#2563eb;
    color:#fff;
    padding:12px;
    font-size:14px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:14px;
}

tr:hover{
    background:#f1f5ff;
}

/* AMOUNT COLORS */
.total{
    font-weight:bold;
    color:#1e3a8a;
}

.advance{
    color:green;
    font-weight:bold;
}

.remaining{
    color:#dc2626;
    font-weight:bold;
}

.paid{
    color:green;
    font-weight:bold;
}

.pending{
    color:orange;
    font-weight:bold;
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