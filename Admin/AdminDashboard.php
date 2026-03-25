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
    background:
        radial-gradient(circle at top left,#dbeafe,transparent 40%),
        radial-gradient(circle at bottom right,#bfdbfe,transparent 40%),
        linear-gradient(135deg,#eef4ff,#f8fbff);
    min-height:100vh;
}

/* HEADER */
.header{
    backdrop-filter: blur(14px);
    background:rgba(37,99,235,0.85);
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    padding:20px 30px;
    color:#fff;
    position:sticky;
    top:0;
    z-index:10;
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
    text-shadow:0 3px 10px rgba(0,0,0,0.25);
}

.logout-btn{
    background:linear-gradient(135deg,#ef4444,#dc2626);
    color:#fff;
    padding:10px 22px;
    border-radius:30px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    box-shadow:0 6px 20px rgba(239,68,68,0.5);
    transition:all 0.3s ease;
}
.logout-btn:hover{
    transform:translateY(-2px) scale(1.05);
}

/* CONTAINER */
.container{
    max-width:1200px;
    margin:40px auto;
    padding:20px;
}

/* SECTION TITLE */
.section-title{
    margin:35px 10px 20px;
    font-size:22px;
    color:#1e3a8a;
    font-weight:700;
    letter-spacing:0.5px;
}

/* GRID */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
}

/* GLASS CARD */
.card{
    position:relative;
    background:rgba(255,255,255,0.6);
    backdrop-filter:blur(18px);
    border-radius:18px;
    padding:28px 22px;
    text-align:center;
    box-shadow:
        0 10px 30px rgba(0,0,0,0.12),
        inset 0 1px 1px rgba(255,255,255,0.6);
    overflow:hidden;
}

/* SHINE EFFECT */
.card::before{
    content:"";
    position:absolute;
    top:-50%;
    left:-60%;
    width:200%;
    height:200%;
    background:linear-gradient(
        120deg,
        transparent 30%,
        rgba(255,255,255,0.6),
        transparent 70%
    );
    transform:rotate(25deg);
    transition:0.6s;
}
.card:hover::before{
    left:120%;
}

/* STAT CARDS */
.card.stat{
    border:1px solid rgba(59,130,246,0.35);
}
.card.stat h2{
    margin:0;
    font-size:38px;
    color:#1e40af;
    font-weight:700;
}
.card.stat p{
    margin-top:10px;
    font-size:15px;
    color:#334155;
    font-weight:500;
}

/* MANAGEMENT CARDS */
.card.manage{
    cursor:pointer;
    text-decoration:none;
    color:#0f172a;
    border:1px solid rgba(34,197,94,0.35);
    transition:all 0.35s ease;
}
.card.manage:hover{
    transform:translateY(-10px) scale(1.03);
    box-shadow:
        0 20px 45px rgba(0,0,0,0.25),
        inset 0 1px 2px rgba(255,255,255,0.7);
}
.card.manage h2{
    margin:0;
    font-size:42px;
}
.card.manage p{
    margin-top:12px;
    font-size:16px;
    font-weight:600;
    letter-spacing:0.3px;
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
            <p>View Bookings</p>
        </a>

        <a href="manage_colleges.php" class="card manage">
        <h2>🏫</h2>
        <p>View Colleges</p>
        </a>
        <a href="manage_payments.php" class="card manage">
        <h2>🏫</h2>
        <p>Manage Payments</p>
        </a>

    </div>

</div>

</body>
</html>
