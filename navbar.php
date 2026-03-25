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
    --bg-dark:#0b0f1a;
    --accent:#7aa2ff;
    --accent-glow:#9bb6ff;
    --text-light:#eaeaff;
    --text-muted:#b7b7d6;
}

/* NAVBAR */
.navbar{
    height:72px;
    background:linear-gradient(135deg,#0b0f1a,#12172a);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 65px;
    position:relative;
    z-index:1000;
    backdrop-filter:blur(14px);
    box-shadow:
        0 20px 60px rgba(0,0,0,0.7),
        inset 0 1px 0 rgba(255,255,255,0.08);
}

/* shimmer */
.navbar::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:2px;
    background:linear-gradient(90deg,transparent,var(--accent-glow),transparent);
    animation:shimmer 6s linear infinite;
}

/* LOGO */
.nav-logo{
    color:var(--text-light);
    font-size:22px;
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
}

/* DROPDOWN */
.dropdown{ position:relative; }

.dropdown-menu{
    position:absolute;
    top:72px;
    left:-30px;
    width:250px;
    background:#11162a;
    border-radius:16px;
    padding:10px 0;
    display:none;
}

.dropdown-menu a{
    display:block;
    padding:12px 20px;
}

.dropdown-menu.show{ display:block; }

/* 🔔 NOTIFICATION ICON */
.noti{
    position:relative;
    font-size:22px;
    cursor:pointer;
    color:white;
}

/* 🔴 BADGE */
.badge{
    position:absolute;
    top:-6px;
    right:-10px;
    background:red;
    color:white;
    font-size:11px;
    padding:2px 6px;
    border-radius:50%;
}

/* ANIM */
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