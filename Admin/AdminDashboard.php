<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

/* ================= BASIC DATA ================= */
$college = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users WHERE user_id != 1"));
$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM events"));
$booking = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings"));
$revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) AS total FROM bookings"));

$recent = mysqli_query($conn,"SELECT * FROM bookings ORDER BY booking_id DESC LIMIT 5");

/* ================= BOOKINGS CHART ================= */
$chartData = mysqli_query($conn,"
SELECT MONTH(booking_date) as month, COUNT(*) as total
FROM bookings
GROUP BY MONTH(booking_date)
ORDER BY MONTH(booking_date)
");

$months = [];
$totals = [];

while($row = mysqli_fetch_assoc($chartData)){
    $months[] = (int)$row['month'];
    $totals[] = (int)$row['total'];
}

/* ================= REVENUE CHART ================= */
$revData = mysqli_query($conn,"
SELECT MONTH(booking_date) as month, SUM(total_price) as total
FROM bookings
GROUP BY MONTH(booking_date)
ORDER BY MONTH(booking_date)
");

$revMonths = [];
$revTotals = [];

while($row = mysqli_fetch_assoc($revData)){
    $revMonths[] = (int)$row['month'];
    $revTotals[] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* HEADER */
.header{
    position:fixed;
    top:0;
    left:250px;
    width:calc(100% - 250px);
    height:70px;
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(10px);
    display:flex;
    align-items:center;
    padding:0 30px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    z-index:1000;
}

.header-title{
    font-size:20px;
    font-weight:600;
    color:#5b21b6;
}

/* CONTENT */
.content{
    margin-left:250px;
    padding:110px 40px 40px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.card{
    background:#fff;
    border-radius:15px;
    padding:25px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.icon{
    font-size:25px;
    margin-bottom:10px;
}

.card h2{
    margin:0;
    font-size:32px;
    color:#6d28d9;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
    margin-top:30px;
}

/* BOX */
.box{
    background:#fff;
    padding:20px;
    border-radius:15px;
}

/* ACTIVITY */
.activity p{
    border-bottom:1px solid #eee;
    padding:8px 0;
    font-size:14px;
}

/* ACTIONS */
.actions{
    margin-top:30px;
}

.actions a{
    display:inline-block;
    background:#7c3aed;
    color:#fff;
    padding:10px 20px;
    border-radius:10px;
    text-decoration:none;
    margin-right:10px;
}

</style>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<!-- HEADER -->
<div class="header">
    <div class="header-title">Admin Dashboard</div>
</div>

<!-- CONTENT -->
<div class="content">

<!-- CARDS -->
<div class="cards">

<div class="card">
    <div class="icon">🎓</div>
    <h2><?php echo $college['total']; ?></h2>
    <p>Total Colleges</p>
</div>

<div class="card">
    <div class="icon">🎉</div>
    <h2><?php echo $event['total']; ?></h2>
    <p>Total Events</p>
</div>

<div class="card">
    <div class="icon">📅</div>
    <h2><?php echo $booking['total']; ?></h2>
    <p>Total Bookings</p>
</div>

<div class="card">
    <div class="icon">💰</div>
    <h2>₹ <?php echo $revenue['total'] ? $revenue['total'] : 0; ?></h2>
    <p>Total Revenue</p>
</div>

</div>

<!-- GRID -->
<div class="grid">

<!-- BOOKINGS CHART -->
<div class="box">
    <h3>Bookings Overview</h3>
    <canvas id="bookingChart"></canvas>
</div>

<!-- RECENT -->
<div class="box activity">
    <h3>Recent Bookings</h3>

    <?php while($row = mysqli_fetch_assoc($recent)){ ?>
        <p>Booking ID: <?php echo $row['booking_id']; ?></p>
    <?php } ?>

</div>

</div>

<!-- REVENUE CHART -->
<div class="box" style="margin-top:30px;">
    <h3>Revenue Overview</h3>
    <canvas id="revenueChart"></canvas>
</div>

<!-- ACTIONS -->
<div class="actions">
    <a href="add_event.php">+ Add Event</a>
    <a href="bookings.php">View Bookings</a>
    <a href="payments.php">Payments</a>
</div>

</div>

<!-- CHART JS -->
<script>
const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

/* BOOKINGS */
const months = <?php echo json_encode($months); ?>;
const totals = <?php echo json_encode($totals); ?>;
const labels = months.map(m => monthNames[m-1]);

new Chart(document.getElementById("bookingChart"), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: "Bookings",
            data: totals
        }]
    }
});

/* REVENUE */
const revMonths = <?php echo json_encode($revMonths); ?>;
const revTotals = <?php echo json_encode($revTotals); ?>;
const revLabels = revMonths.map(m => monthNames[m-1]);

new Chart(document.getElementById("revenueChart"), {
    type: 'line',
    data: {
        labels: revLabels,
        datasets: [{
            label: "Revenue",
            data: revTotals
        }]
    }
});
</script>

</body>
</html>