<?php
session_start();
include("../db.php");

// total colleges (exclude admin)
$college_q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE user_id != 1");
$college = mysqli_fetch_assoc($college_q);

// total events
$event_q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events");
$event = mysqli_fetch_assoc($event_q);

// total bookings
$booking_q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$booking = mysqli_fetch_assoc($booking_q);

// total revenue
$revenue_q = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM bookings");
$revenue = mysqli_fetch_assoc($revenue_q);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Admin Dashboard</title>

<style>
body{
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:#f2f8ff;
}
.container{
    max-width:1100px;
    margin:40px auto;
    padding:20px;
}
h1{
    color:#1f4fd8;
    margin-bottom:30px;
}
.cards{
    display:flex;
    flex-wrap:wrap;
}
.card{
    width:220px;
    padding:25px;
    margin:15px;
    background:#ffffff;
    border-radius:10px;
    border-top:5px solid #4da6ff;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    text-decoration:none;
    color:#333;
    transition:0.3s;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 6px 15px rgba(0,0,0,0.15);
}
.card h2{
    margin:0;
    font-size:26px;
    color:#1f4fd8;
}
.card p{
    margin-top:8px;
    font-size:15px;
}
.manage{
    border-top-color:#28a745;
}
</style>

</head>
<body>

<div class="container">
<h1>Admin Dashboard</h1>

<div class="cards">

    <!-- STATS CARDS -->
    <a href="colleges.php" class="card">
        <h2><?php echo $college['total']; ?></h2>
        <p>Total Colleges</p>
    </a>

    <a href="manage_events.php" class="card">
        <h2><?php echo $event['total']; ?></h2>
        <p>Total Events</p>
    </a>

    

    <a href="manage_booking.php" class="card">
        <h2>₹ <?php echo $revenue['total']; ?></h2>
        <p>Total Revenue</p>
    </a>

    <!-- MANAGEMENT CARDS -->
    <a href="manage_events.php" class="card manage">
        <h2>📅</h2>
        <p>Manage Events</p>
    </a>

    <a href="manage_packages.php" class="card manage">
        <h2>📦</h2>
        <p>Manage Packages</p>
    </a>

    <a href="manage_food.php" class="card manage">
        <h2>🍽</h2>
        <p>Manage Food</p>
    </a>

    <a href="manage_venues.php" class="card manage">
        <h2>🏛</h2>
        <p>Manage Venues</p>
    </a>

    <a href="manage_decorations.php" class="card manage">
        <h2>🎉</h2>
        <p>Manage Decorations</p>
    </a>

    <a href="manage_coverage.php" class="card manage">
        <h2>📸</h2>
        <p>Manage Coverage</p>
    </a>

    <a href="manage_seats.php" class="card manage">
        <h2>💺</h2>
        <p>Manage Seats</p>
    </a>

    <a href="manage_bookings.php" class="card manage">
        <h2>📑</h2>
        <p>Manage Bookings</p>
    </a>

</div>
</div>

</body>
</html>
