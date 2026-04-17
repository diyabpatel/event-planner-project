<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

/* DATA */
$college = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users WHERE user_id != 1"));
$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM events"));
$booking = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings"));
$revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) AS total FROM bookings"));

$recent = mysqli_query($conn,"SELECT * FROM bookings ORDER BY booking_id DESC LIMIT 5");

/* BOOKINGS */
$chartData = mysqli_query($conn,"
SELECT MONTH(event_date) as month, COUNT(*) as total
FROM bookings
GROUP BY MONTH(event_date)
");

$months = [];
$totals = [];

while($row = mysqli_fetch_assoc($chartData)){
    $months[] = (int)$row['month'];
    $totals[] = (int)$row['total'];
}

/* REVENUE */
$revData = mysqli_query($conn,"
SELECT MONTH(event_date) as month, SUM(total_price) as total
FROM bookings
GROUP BY MONTH(event_date)
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

/* BODY */
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f8fafc;
    color:#1f2937;
}

/* HEADER */
.header{
    position:fixed;
    top:0;
    left:250px;
    width:calc(100% - 250px);
    height:70px;
    background:#ffffff;
    display:flex;
    align-items:center;
    padding:0 30px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    z-index:1000;
}

.header-title{
    font-size:22px;
    font-weight:600;
    color:#7c3aed;
}

/* CONTENT */
.content{
    margin-left:250px;
    margin-top:30px;
    padding:30px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
}

.card{
    background:#ffffff;
    border-radius:20px;
    padding:25px;
    text-align:center;
    box-shadow:0 10px 25px rgba(124,58,237,0.08);
    border:1px solid #f1f5f9;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 35px rgba(124,58,237,0.2);
}

.card h2{
    font-size:32px;
    color:#7c3aed;
}

.card p{
    color:#6b7280;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:25px;
    margin-top:30px;
}

/* BOX */
.box{
    background:#ffffff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(124,58,237,0.08);
    border:1px solid #f1f5f9;
}

.box h3{
    color:#7c3aed;
}

/* RECENT */
.activity p{
    border-bottom:1px solid #f1f5f9;
    padding:10px 0;
    color:#374151;
}

</style>

</head>

<body>

<?php include("admin_sidebar.php"); ?>



<div class="content">

<div class="cards">

<div class="card">
    <h2><?php echo $college['total']; ?></h2>
    <p>Total Colleges</p>
</div>

<div class="card">
    <h2><?php echo $event['total']; ?></h2>
    <p>Total Events</p>
</div>

<div class="card">
    <h2><?php echo $booking['total']; ?></h2>
    <p>Total Bookings</p>
</div>

<div class="card">
    <h2>₹ <?php echo $revenue['total'] ? $revenue['total'] : 0; ?></h2>
    <p>Total Revenue</p>
</div>

</div>

<div class="grid">

<div class="box">
    <h3>Bookings Overview</h3>
    <canvas id="bookingChart"></canvas>
</div>

<div class="box activity">
    <h3>Recent Bookings</h3>
    <?php while($row = mysqli_fetch_assoc($recent)){ ?>
        <p>Booking ID: <?php echo $row['booking_id']; ?></p>
    <?php } ?>
</div>

</div>

<div class="box" style="margin-top:30px;">
    <h3>Revenue Overview</h3>
    <canvas id="revenueChart"></canvas>
</div>

</div>

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
            data: totals,
            backgroundColor: ["#7c3aed","#a78bfa","#c4b5fd","#ddd6fe"],
            borderRadius:10
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
            data: revTotals,
            borderColor:"#7c3aed",
            backgroundColor:"rgba(124,58,237,0.1)",
            fill:true,
            tension:0.4
        }]
    }
});
</script>

</body>
</html>