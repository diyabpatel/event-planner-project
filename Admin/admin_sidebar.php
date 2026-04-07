<style>
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:#f9f7ff;
}

/* 💜 SIDEBAR (SOFT WHITE LOOK) */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:250px;
    height:100%;
    background:#ffffff;
    padding-top:20px;
    box-shadow:4px 0 25px rgba(124,58,237,0.08);
}

/* TITLE */
.sidebar h2{
    color:#7c3aed;
    text-align:center;
    margin-bottom:30px;
    font-weight:700;
}

/* MENU LINKS */
.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    color:#6b7280;
    padding:12px 20px;
    text-decoration:none;
    border-radius:10px;
    margin:6px 12px;
    transition:0.3s;
    font-size:15px;
}

/* HOVER EFFECT (💜 GLOW + MOVE) */
.sidebar a:hover{
    background:#ede9fe;
    color:#7c3aed;
    transform:translateX(6px);
    box-shadow:0 5px 15px rgba(124,58,237,0.2);
}

/* ACTIVE */
.sidebar a.active{
    background:#7c3aed;
    color:#fff;
    box-shadow:0 5px 15px rgba(124,58,237,0.3);
}

/* 💜 LOGOUT BUTTON (PERFECT) */
.logout-btn{
    position:absolute;
    bottom:20px;
    left:15px;
    right:15px;
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:white;
    font-color:#fff;
    text-align:center;
    padding:12px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

/* LOGOUT HOVER */
.logout-btn:hover{
    transform:scale(1.05);
    box-shadow:0 8px 20px rgba(124,58,237,0.4);
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

    <a href="AdminDashboard.php" class="active">🏠 Dashboard</a>
    <a href="manage_events.php">📅 Manage Events</a>
    <a href="manage_food.php">🍽 Manage Food</a>
    <a href="manage_venues.php">🏛 Manage Venues</a>
    <a href="manage_decorations.php">🎉 Decorations</a>
    <a href="manage_coverage.php">📸 Coverage</a>
    <a href="manage_seats.php">💺 Seats</a>
    <a href="manage_bookings.php">📄 Bookings</a>
    <a href="manage_colleges.php">🏫 Colleges</a>
    <a href="manage_payments.php">💳 Payments</a>
    <a href="revenue_history.php">📊 Revenue</a>

    <a href="../logout.php" class="logout-btn">Logout</a>
</div>