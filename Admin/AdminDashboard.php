<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$college = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users WHERE user_id != 1"));
$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM events"));
$booking = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings"));
$revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) AS total FROM bookings"));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* 💜 SIDEBAR */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:250px;
    height:100%;
    background:linear-gradient(180deg,#7c3aed,#5b21b6);
    padding-top:20px;
}

.sidebar h2{
    color:#fff;
    text-align:center;
    margin-bottom:25px;
}

.sidebar a{
    display:block;
    color:#e0e7ff;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.15);
    padding-left:28px;
}

/* 💜 HEADER (MODERN CLEAN) */
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
    justify-content:space-between;
    padding:0 30px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
}

.header-title{
    font-size:20px;
    font-weight:600;
    color:#5b21b6;
}


/* 💜 CONTENT */
.content{
    margin-left:250px;
    padding:100px 40px 40px;
}

/* TITLE */
.section-title{
    font-size:26px;
    font-weight:600;
    color:#4c1d95;
    margin-bottom:25px;
}

/* 💜 CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
}

.card{
    background:rgba(255,255,255,0.6);
    backdrop-filter:blur(12px);
    border-radius:20px;
    padding:35px 20px;
    text-align:center;
    border:1px solid rgba(124,58,237,0.2);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 10px 25px rgba(124,58,237,0.2);
}

.card h2{
    margin:0;
    font-size:38px;
    color:#6d28d9;
}

.card p{
    margin-top:10px;
    color:#6b7280;
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

<div class="section-title">Overview</div>

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

</div>

</body>
</html>