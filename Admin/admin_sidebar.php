<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#f8f7ff,#ede9fe);
}

/* 💎 SIDEBAR */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:260px;
    height:100vh;
     overflow:hidden; 
    display:flex;
    flex-direction:column;

    background:rgba(255,255,255,0.65);
    backdrop-filter:blur(18px);

    border-right:1px solid rgba(255,255,255,0.3);

    padding-top:20px;
    z-index:1000;
}

/* TITLE */
.sidebar h2{
    text-align:center;
    color:#6d28d9;
    margin-bottom:25px;
    font-weight:700;
}

/* 🧠 MENU SCROLL */
.menu{
    flex:1;
    overflow-y:auto;
    padding-bottom:80px; /* ✅ IMPORTANT space for logout */
}

/* SCROLLBAR */
.menu::-webkit-scrollbar{
    width:5px;
}
.menu::-webkit-scrollbar-thumb{
    background:#c4b5fd;
    border-radius:10px;
}

/* MENU LINKS */
.sidebar a{
    display:flex;
    align-items:center;
    gap:14px;
    padding:13px 20px;
    margin:8px 14px;

    color:#6b7280;
    text-decoration:none;
    border-radius:12px;

    transition:all 0.25s ease;
}

/* ICON */
.sidebar a i{
    font-size:17px;
}

/* HOVER */
.sidebar a:hover{
    background:#ede9fe;
    color:#6d28d9;
    transform:translateX(5px);
}

/* ✅ CLEAN ACTIVE TAB */
.sidebar a.active{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:#fff;

    box-shadow:0 6px 15px rgba(124,58,237,0.25);
}

/* LEFT INDICATOR */
.sidebar a.active::before{
    content:'';
    position:absolute;
    left:0;
    top:20%;
    width:4px;
    height:60%;
    border-radius:10px;
    background:#fff;
}

/* 🧠 MENU SCROLL */
.menu{
    flex:1;
    overflow-y:auto;
    padding-bottom:80px; /* ✅ IMPORTANT space for logout */
}

/* 💜 LOGOUT BUTTON FIXED */
/* LOGOUT AS MENU ITEM */
.logout-btn{
    background:linear-gradient(135deg,#7c3aed,#4c1d95);
    color:white !important;

    justify-content:center;
    margin-top:15px;

    border-radius:12px;
}
/* CONTENT */
.content{
    margin-left:260px;
    padding:25px;
}

</style>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin Panel</h2>

       <div class="menu">

    <a href="AdminDashboard.php" class="<?= ($current_page=='AdminDashboard.php')?'active':'' ?>">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <a href="manage_events.php" class="<?= ($current_page=='manage_events.php')?'active':'' ?>">
        <i class="fas fa-calendar"></i> Events
    </a>

    <a href="manage_food.php" class="<?= ($current_page=='manage_food.php')?'active':'' ?>">
        <i class="fas fa-utensils"></i> Food
    </a>

    <a href="manage_venues.php" class="<?= ($current_page=='manage_venues.php')?'active':'' ?>">
        <i class="fas fa-building"></i> Venues
    </a>

    <a href="manage_decorations.php" class="<?= ($current_page=='manage_decorations.php')?'active':'' ?>">
        <i class="fas fa-star"></i> Decorations
    </a>

    <a href="manage_coverage.php" class="<?= ($current_page=='manage_coverage.php')?'active':'' ?>">
        <i class="fas fa-camera"></i> Coverage
    </a>

    <a href="manage_seats.php" class="<?= ($current_page=='manage_seats.php')?'active':'' ?>">
        <i class="fas fa-chair"></i> Seats
    </a>

    <a href="manage_bookings.php" class="<?= ($current_page=='manage_bookings.php')?'active':'' ?>">
        <i class="fas fa-file"></i> Bookings
    </a>

    <a href="manage_colleges.php" class="<?= ($current_page=='manage_colleges.php')?'active':'' ?>">
        <i class="fas fa-school"></i> Colleges
    </a>

    <a href="manage_payments.php" class="<?= ($current_page=='manage_payments.php')?'active':'' ?>">
        <i class="fas fa-credit-card"></i> Payments
    </a>

    <a href="revenue_history.php" class="<?= ($current_page=='revenue_history.php')?'active':'' ?>">
        <i class="fas fa-chart-line"></i> Revenue
    </a>

    <!-- 🔥 LOGOUT INSIDE MENU -->
    <a href="../logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

</div>
</div>