<!-- FONT AWESOME (ICONS) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* 💎 GLASS SIDEBAR */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:250px;
    height:100vh; /* 🔥 IMPORTANT */
    
    display:flex;
    flex-direction:column; /* 🔥 */
    
    background:rgba(255,255,255,0.6);
    backdrop-filter:blur(15px);
    border-right:1px solid rgba(255,255,255,0.3);
    box-shadow:4px 0 25px rgba(124,58,237,0.15);
    padding-top:20px;
}

/* TITLE */
.sidebar h2{
    text-align:center;
    color:#7c3aed;
    margin-bottom:30px;
    font-weight:700;
}

/* MENU */
.sidebar a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 20px;
    margin:8px 12px;
    color:#6b7280;
    text-decoration:none;
    border-radius:12px;
    position:relative;
    overflow:hidden;
    transition:0.3s ease;
}

/* ICON */
.sidebar a i{
    font-size:16px;
}

/* ✨ HOVER EFFECT (ANIMATED LIGHT SWIPE) */
.sidebar a::before{
    content:'';
    position:absolute;
    left:-100%;
    top:0;
    width:100%;
    height:100%;
    background:linear-gradient(120deg,transparent,rgba(124,58,237,0.3),transparent);
    transition:0.5s;
}

.sidebar a:hover::before{
    left:100%;
}

/* HOVER STYLE */
.sidebar a:hover{
    background:#ede9fe;
    color:#7c3aed;
    transform:translateX(6px);
}

/* ACTIVE LINK */
.sidebar a.active{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:#fff;
    box-shadow:0 8px 20px rgba(124,58,237,0.4);
}

/* 💜 LOGOUT BUTTON */
.menu{
    flex:1; /* 🔥 pushes logout down */
}

/* REMOVE absolute positioning */
.logout-btn{
    margin:15px;
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:white;
    text-align:center;
    padding:12px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.logout-btn:hover{
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(124,58,237,0.5);
}

/* CONTENT SHIFT */
.content{
    margin-left:250px;
    padding:20px;
}
</style>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin Panel</h2>
 <div class="menu"> 

    <a href="AdminDashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="manage_events.php"><i class="fas fa-calendar"></i> Events</a>
    <a href="manage_food.php"><i class="fas fa-utensils"></i> Food</a>
    <a href="manage_venues.php"><i class="fas fa-building"></i> Venues</a>
    <a href="manage_decorations.php"><i class="fas fa-star"></i> Decorations</a>
    <a href="manage_coverage.php"><i class="fas fa-camera"></i> Coverage</a>
    <a href="manage_seats.php"><i class="fas fa-chair"></i> Seats</a>
    <a href="manage_bookings.php"><i class="fas fa-file"></i> Bookings</a>
    <a href="manage_colleges.php"><i class="fas fa-school"></i> Colleges</a>
    <a href="manage_payments.php"><i class="fas fa-credit-card"></i> Payments</a>
    <a href="revenue_history.php"><i class="fas fa-chart-line"></i> Revenue</a>

    </div>

    <a href="../logout.php" class="logout-btn">Logout</a>
</div>