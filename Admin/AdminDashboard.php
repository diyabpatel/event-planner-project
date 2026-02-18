<?php
session_start();
include("../db.php");

// protect page
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

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
<title>Admin Dashboard</title>

<style>
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:linear-gradient(135deg,#eef4ff,#f8fbff);
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#2563eb,#1e40af);
    padding:20px 30px;
    color:#fff;
}

.header-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header-title{
    font-size:26px;
    font-weight:600;
    letter-spacing:1px;
}

.logout-btn{
    background:#ef4444;
    color:#fff;
    padding:8px 18px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    transition:0.25s;
}

.logout-btn:hover{
    background:#dc2626;
}

/* CONTAINER */
.container{
    max-width:1200px;
    margin:40px auto;
    padding:20px;
}

/* SECTION TITLE */
.section-title{
    margin:25px 10px 15px;
    font-size:20px;
    color:#1e3a8a;
    font-weight:600;
}

/* CARDS GRID */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

/* COMMON CARD */
.card{
    background:#ffffff;
    border-radius:14px;
    padding:25px 20px;
    text-align:center;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* STAT CARDS */
.card.stat{
    border-top:6px solid #3b82f6;
}
.card.stat h2{
    margin:0;
    font-size:34px;
    color:#1e40af;
}
.card.stat p{
    margin-top:10px;
    font-size:15px;
    color:#475569;
}

/* MANAGE CARDS */
.card.manage{
    border-top:6px solid #22c55e;
    cursor:pointer;
    text-decoration:none;
    color:#0f172a;
    transition:all 0.25s ease;
}
.card.manage:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 30px rgba(0,0,0,0.15);
}
.card.manage h2{
    margin:0;
    font-size:36px;
}
.card.manage p{
    margin-top:10px;
    font-size:15px;
    font-weight:500;
}
</style>

</head>
<body>

<div class="header">
    <div class="header-container">
        <div class="header-title">Admin Dashboard</div>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">

    <!-- STATS -->
    <div class="section-title">Overview</div>
    <div class="cards">

        <div class="card stat">
            <h2><?php echo $college['total']; ?></h2>
            <p>Total Colleges</p>
        </div>

        <div class="card stat">
            <h2><?php echo $event['total']; ?></h2>
            <p>Total Events</p>
        </div>

        <div class="card stat">
            <h2><?php echo $booking['total']; ?></h2>
            <p>Total Bookings</p>
        </div>

        <div class="card stat">
            <h2>₹ <?php echo $revenue['total'] ? $revenue['total'] : 0; ?></h2>
            <p>Total Revenue</p>
        </div>

    </div>

    <!-- MANAGEMENT -->
    <div class="section-title">Management</div>
    <div class="cards">

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
