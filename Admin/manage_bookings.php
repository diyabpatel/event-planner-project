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

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* HEADER */
.header{
    background:white;
    color:#5b21b6;
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 10px 30px rgba(91,33,182,0.15);
    position:sticky;
    top:0;
    z-index:10;
}

.header h2{
    margin:0;
    font-size:22px;
    font-weight:600;
}

/* BACK BUTTON */
.back-btn{
    background:linear-gradient(135deg,#f43f5e,#e11d48);
    padding:10px 18px;
    border-radius:30px;
    text-decoration:none;
    color:white;
    font-size:14px;
    font-weight:600;
    transition:0.3s;
}

.back-btn:hover{
    opacity:0.85;
}

/* CONTAINER */
.container{
    padding:30px;
}

/* ================= TABLE ================= */

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(91,33,182,0.15);
}

/* HEADER */
th{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:white;
    padding:14px;
    font-size:14px;
    text-align:center;
    font-weight:600;
}

/* DATA */
td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:14px;
}

/* ROW HOVER */
tr:hover{
    background:#f5f3ff;
}

/* AMOUNTS */
.total{
    font-weight:700;
    color:#5b21b6;
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
    background:rgba(34,197,94,0.15);
    color:#15803d;
    padding:6px 14px;
    border-radius:20px;
    font-weight:600;
}

.pending{
    background:rgba(249,115,22,0.15);
    color:#c2410c;
    padding:6px 14px;
    border-radius:20px;
    font-weight:600;
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