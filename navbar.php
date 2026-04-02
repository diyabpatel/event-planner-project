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
    --bg-soft:#f5f3ff;
    --purple:#7c3aed;
    --purple-light:#a78bfa;
    --purple-glow:#c4b5fd;
    --text-dark:#1f1f2e;
    --text-muted:#6b7280;
}

/* BODY FIX (VERY IMPORTANT) */
body{
    margin:0;
    padding-top:80px; /* navbar overlap fix */
    font-family:'Segoe UI', system-ui;
    background:var(--bg-main);
}

/* NAVBAR */
.navbar{
    height:72px;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 65px;

    position:fixed;   /* 🔥 FIXED NAVBAR */
    top:0;
    left:0;
    width:100%;

    z-index:1000;
    backdrop-filter:blur(10px);

    box-shadow:
        0 10px 40px rgba(124,58,237,0.15),
        inset 0 1px 0 rgba(255,255,255,0.7);
}

/* TOP SHIMMER LINE */
.navbar::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:3px;
    background:linear-gradient(90deg,transparent,var(--purple),transparent);
    animation:shimmer 5s linear infinite;
}

/* LOGO */
.nav-logo{
    color:var(--purple);
    font-size:24px;
    font-weight:700;
}

/* MENU */
.nav-menu{
    display:flex;
    align-items:center;
    gap:28px;
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
}

/* HOVER EFFECT */
.nav-menu a:hover,
.dropdown-toggle:hover{
    color:var(--purple);
}

/* UNDERLINE ANIMATION */
.nav-menu a::after,
.dropdown-toggle::after{
    content:"";
    position:absolute;
    bottom:-4px;
    left:0;
    width:0%;
    height:2px;
    background:var(--purple);
    transition:0.3s;
}

.nav-menu a:hover::after,
.dropdown-toggle:hover::after{
    width:100%;
}

/* DROPDOWN */
.dropdown{ position:relative; }

.dropdown-menu{
    position:absolute;
    top:72px;
    left:-30px;
    width:250px;
    background:#ffffff;
    border-radius:16px;
    padding:10px 0;
    display:none;

    box-shadow:0 20px 40px rgba(124,58,237,0.2);
}

.dropdown-menu a{
    display:block;
    padding:12px 20px;
    color:var(--text-dark);
    transition:0.3s;
}

.dropdown-menu a:hover{
    background:#f5f3ff;
    color:var(--purple);
}

.dropdown-menu.show{ display:block; }

/* 🔔 NOTIFICATION ICON */
.noti{
    position:relative;
    font-size:22px;
    cursor:pointer;
    color:var(--purple);
    transition:0.3s;
}

.noti:hover{
    transform:scale(1.1);
}

/* 🔴 BADGE */
.badge{
    position:absolute;
    top:-6px;
    right:-10px;
    background:linear-gradient(135deg,#ef4444,#dc2626);
    color:white;
    font-size:11px;
    padding:2px 6px;
    border-radius:50%;
    box-shadow:0 0 10px rgba(239,68,68,0.6);
}

/* ANIMATION */
@keyframes shimmer{
    0%{left:-100%}
    50%{left:100%}
    100%{left:100%}
}

</style>

<div class="navbar">

    <div class="nav-logo">EventHub</div>

    <div class="nav-menu">

        <a href="/event-planner-project/index.php">Home</a>

        <div class="dropdown">
            <span class="dropdown-toggle" id="eventToggle">Events ▾</span>
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

<script>
const toggle = document.getElementById("eventToggle");
const menu   = document.getElementById("eventMenu");

toggle.addEventListener("click", function(e){
    e.stopPropagation();
    menu.classList.toggle("show");
});

document.addEventListener("click", function(){
    menu.classList.remove("show");
});
</script>