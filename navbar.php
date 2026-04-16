<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include("db.php");

/* ===== NOTIFICATION COUNT ===== */
$noti_count = 0;

if(isset($_SESSION['user_id'])){
    $uid = $_SESSION['user_id'];

    $q = mysqli_query($conn,"
    SELECT COUNT(*) AS total 
    FROM bookings 
    WHERE user_id='$uid' AND is_read=0 AND notification!=''
    ");

    $data = mysqli_fetch_assoc($q);
    $noti_count = $data['total'];
}
?>

<style>

:root{
    --bg-main:#ffffff;
    --bg-soft:#f6f4ff;
    --accent:#7c3aed;
    --accent-light:#a78bfa;
    --accent-glow:#c4b5fd;
    --text-dark:#1e1b4b;
    --text-muted:#6d6aa3;
}

/* 📌 FIXED NAVBAR */
.navbar{
    height:85px;
    background:linear-gradient(135deg,#ffffff,#f6f4ff);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    box-sizing:border-box;
    z-index:9999;
    backdrop-filter:blur(12px);
    box-shadow:0 10px 30px rgba(124,58,237,0.15);
    overflow:visible; /* 🔥 FIX */
}

/* page spacing */
body{
    margin:0;
    padding-top:80px;
}

/* shimmer */
.navbar::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:3px;
    background:linear-gradient(90deg,transparent,var(--accent-light),transparent);
    animation:shimmer 6s linear infinite;
}

/* LOGO */
.nav-logo{
    display:flex;
    align-items:center;   /* 🔥 centers vertically */
    height:100%;
}
.nav-logo img{
    height:82px;   /* adjust size as needed */
    width:auto;
    display:block;
    object-fit:contain;
}

/* MENU */
.nav-menu{
    display:flex;
    align-items:center;
    gap:16px;
    flex-wrap:nowrap;
    overflow:visible;   /* 🔥 IMPORTANT FIX */
}

/* LINKS */
.nav-menu a,
.dropdown-toggle{
    color:var(--text-muted);
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    position:relative;
    transition:0.3s;
    padding:6px 4px;
    white-space:nowrap;
}

/* hover */
.nav-menu a:hover,
.dropdown-toggle:hover{
    color:var(--accent);
}

/* ACTIVE TAB */
.nav-menu a.active{
    color:var(--accent);
    font-weight:600;
}

/* glow underline */
.nav-menu a.active::after{
    content:"";
    position:absolute;
    bottom:-6px;
    left:0;
    width:100%;
    height:3px;
    background:linear-gradient(90deg,var(--accent),var(--accent-light));
    border-radius:10px;
    box-shadow:0 0 12px var(--accent-glow);
}

/* hover underline */
.nav-menu a::after,
.dropdown-toggle::after{
    content:"";
    position:absolute;
    bottom:-6px;
    left:0;
    width:0%;
    height:2px;
    background:var(--accent);
    transition:0.3s;
}

.nav-menu a:hover::after,
.dropdown-toggle:hover::after{
    width:100%;
}

/* DROPDOWN */
.dropdown{
    position:relative;
    z-index:10000; /* 🔥 FIX */
}

.dropdown-menu{
    position:absolute;
    top:72px;
    left:0; /* 🔥 FIXED alignment */
    width:240px;
    background:#ffffff;
    border-radius:16px;
    padding:10px 0;
    display:none;
    box-shadow:0 15px 40px rgba(124,58,237,0.15);
    border:1px solid #eee;
    z-index:9999;
    animation:fadeIn 0.3s ease;
}

.dropdown-menu a{
    display:block;
    padding:12px 20px;
}

.dropdown-menu a:hover{
    background:var(--bg-soft);
    color:var(--accent);
}

.dropdown-menu.show{
    display:block;
}

/* animation for dropdown */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* 🔔 NOTI */
.noti{
    position:relative;
    font-size:20px;
    color:var(--accent);
}

/* badge */
.badge{
    position:absolute;
    top:-6px;
    right:-10px;
    background:linear-gradient(135deg,#ff4d6d,#ff758f);
    color:white;
    font-size:11px;
    padding:3px 6px;
    border-radius:50%;
}

/* shimmer animation */
@keyframes shimmer{
    0%{left:-100%}
    50%{left:100%}
    100%{left:100%}
}

/* 📱 RESPONSIVE */
@media (max-width:900px){
    .navbar{
        padding:0 15px;
    }

    .nav-menu{
        gap:12px;
    }

    .nav-menu a{
        font-size:14px;
    }
}

</style>

<div class="navbar">

    <div class="nav-logo">
    <img src="/event-planner-project/uploads/images/logo/eventhub_logo.png" alt="EventHub Logo">
</div>

    <div class="nav-menu">

        <a href="/event-planner-project/index.php">Home</a>

        <div class="dropdown">
            
           <a href="#" class="dropdown-toggle" id="eventToggle">Events &#9662;</a>

            <div class="dropdown-menu" id="eventMenu">
                <?php
                $res = mysqli_query($conn,"SELECT * FROM events ORDER BY event_name");
                while($row = mysqli_fetch_assoc($res)){
                   echo '<a href="/event-planner-project/events/'.$row['page'].'">'.$row['event_name'].'</a>';
                }
                ?>
            </div>
        </div>

        <a href="/event-planner-project/gallery.php">Gallery</a>

        <?php if(isset($_SESSION['user_id'])){ ?>

            <a href="/event-planner-project/User/my_bookings.php">My Bookings</a>

            <!-- 🔔 NOTIFICATION -->
            <a href="/event-planner-project/User/notifications.php" class="noti">
                🔔
                <?php if($noti_count > 0){ ?>
                    <span class="badge"><?php echo $noti_count; ?></span>
                <?php } ?>
            </a>

            <a href="/event-planner-project/logout.php">Logout</a>

        <?php } else { ?>

            <a href="/event-planner-project/login.php">Login</a>

        <?php } ?>

    </div>

</div>


<!-- ✅ FIXED JAVASCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function(){

    const toggle = document.getElementById("eventToggle");
    const menu   = document.getElementById("eventMenu");

    if(toggle && menu){

        toggle.addEventListener("click", function(e){
            e.preventDefault();
            e.stopPropagation();
            menu.classList.toggle("show");
        });

        // 🔥 IMPORTANT FIX
        document.addEventListener("click", function(e){
            if(!toggle.contains(e.target) && !menu.contains(e.target)){
                menu.classList.remove("show");
            }
        });
    }

    const links = document.querySelectorAll(".nav-menu a");
    const current = window.location.pathname;

    links.forEach(link => {
        if(link.getAttribute("href") === current){
            link.classList.add("active");
        }
    });

});
</script>