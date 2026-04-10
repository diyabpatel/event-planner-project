<!-- FONT AWESOME (ICONS) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* FONT */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(135deg,#f8f7ff,#ede9fe);
}

/* 💎 SIDEBAR GLASS */
.sidebar{
position:fixed;
left:0;
top:0;
width:260px;
height:100vh;
display:flex;
flex-direction:column;

background:rgba(255,255,255,0.65);
backdrop-filter:blur(18px);
border-right:1px solid rgba(255,255,255,0.3);

box-shadow:
0 10px 40px rgba(124,58,237,0.15),
inset 0 0 25px rgba(255,255,255,0.4);

padding:20px 15px;
}

/* LOGO / TITLE */
.sidebar h2{
text-align:center;
font-size:22px;
font-weight:700;
margin-bottom:30px;

background:linear-gradient(135deg,#7c3aed,#c4b5fd);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* MENU */
.menu{
flex:1;
overflow-y:auto;
}

/* LINK BASE */
.sidebar a{
display:flex;
align-items:center;
gap:14px;

padding:13px 18px;
margin:8px 6px;

border-radius:14px;

color:#6b7280;
text-decoration:none;
font-size:14px;

position:relative;
overflow:hidden;

transition:all 0.3s ease;
}

/* ICON */
.sidebar a i{
font-size:16px;
transition:0.3s;
}

/* ✨ SHINE EFFECT */
.sidebar a::before{
content:'';
position:absolute;
top:0;
left:-120%;
width:120%;
height:100%;

background:linear-gradient(
120deg,
transparent,
rgba(255,255,255,0.7),
transparent
);

transition:0.6s;
}

/* HOVER */
.sidebar a:hover::before{
left:120%;
}

.sidebar a:hover{
background:rgba(124,58,237,0.1);
color:#7c3aed;
transform:translateX(8px) scale(1.02);
box-shadow:0 6px 18px rgba(124,58,237,0.15);
}

/* 🔥 ACTIVE TAB (CURRENT PAGE) */
.sidebar a.active{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;

box-shadow:
0 10px 25px rgba(124,58,237,0.4),
0 0 15px rgba(124,58,237,0.6);

transform:scale(1.03);
}

/* ACTIVE ICON GLOW */
.sidebar a.active i{
color:white;
text-shadow:0 0 10px rgba(255,255,255,0.8);
}

/* LEFT GLOW BAR */
.sidebar a.active::after{
content:'';
position:absolute;
left:0;
top:20%;
width:4px;
height:60%;
border-radius:10px;
background:#fff;
box-shadow:0 0 10px #fff;
}

/* 💜 LOGOUT PERFECT BOTTOM */
.logout-btn{
margin-top:auto; /* 🔥 pushes to bottom perfectly */
margin-bottom:10px;

background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;

text-align:center;
padding:13px;
border-radius:30px;

font-weight:600;
letter-spacing:0.5px;

transition:0.3s;
}

/* LOGOUT HOVER */
.logout-btn:hover{
transform:scale(1.05);
box-shadow:
0 10px 30px rgba(124,58,237,0.5),
0 0 20px rgba(124,58,237,0.6);
}

/* CONTENT SHIFT */
.content{
margin-left:260px;
padding:25px;
}

/* SCROLLBAR (PREMIUM TOUCH) */
.menu::-webkit-scrollbar{
width:6px;
}

.menu::-webkit-scrollbar-thumb{
background:#c4b5fd;
border-radius:10px;
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