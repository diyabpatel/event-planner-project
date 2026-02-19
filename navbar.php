<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include("db.php");
?>

<style>
/* ================= ROOT ================= */
:root{
    --bg-dark:#0b0f1a;
    --accent:#7aa2ff;
    --accent-glow:#9bb6ff;
    --text-light:#eaeaff;
    --text-muted:#b7b7d6;
}

/* ================= NAVBAR ================= */
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

/* SHIMMER LINE */
.navbar::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:2px;
    background:linear-gradient(
        90deg,
        transparent,
        var(--accent-glow),
        transparent
    );
    animation:shimmer 6s linear infinite;
}

/* ================= LOGO ================= */
.nav-logo{
    color:var(--text-light);
    font-size:22px;
    font-weight:700;
    letter-spacing:1px;
    text-shadow:0 0 10px rgba(122,162,255,0.4);
}

/* ================= MENU ================= */
.nav-menu{
    display:flex;
    align-items:center;
    gap:32px;
}

.nav-menu a,
.dropdown-toggle{
    color:var(--text-muted);
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    position:relative;
    padding:6px 2px;
    cursor:pointer;
    transition:all 0.35s ease;
}

/* underline glow */
.nav-menu a::after,
.dropdown-toggle::after{
    content:"";
    position:absolute;
    left:50%;
    bottom:-8px;
    width:0;
    height:2px;
    background:linear-gradient(90deg,var(--accent),var(--accent-glow));
    box-shadow:0 0 12px var(--accent-glow);
    transition:all 0.35s ease;
}

.nav-menu a:hover::after,
.dropdown-toggle:hover::after{
    width:100%;
    left:0;
}

.nav-menu a:hover,
.dropdown-toggle:hover{
    color:var(--text-light);
    text-shadow:0 0 10px rgba(155,182,255,0.6);
}

/* ================= DROPDOWN ================= */
.dropdown{
    position:relative;
}

.dropdown-menu{
    position:absolute;
    top:72px;           /* navbar height */
    left:-30px;
    width:250px;
    background:linear-gradient(180deg,#11162a,#0b0f1a);
    border-radius:16px;
    padding:12px 0;
    display:none;
    z-index:2000;

    box-shadow:
        0 40px 80px rgba(0,0,0,0.85),
        inset 0 0 0 1px rgba(255,255,255,0.06);

    backdrop-filter:blur(18px);
    animation:dropdownFade 0.4s ease forwards;
}

/* arrow */
.dropdown-menu::before{
    content:"";
    position:absolute;
    top:-10px;
    left:45px;
    width:18px;
    height:18px;
    background:#11162a;
    transform:rotate(45deg);
}

/* dropdown items */
.dropdown-menu a{
    display:block;
    padding:14px 24px;
    color:var(--text-muted);
    font-size:14px;
    font-weight:500;
    transition:all 0.35s ease;
}

.dropdown-menu a:hover{
    color:var(--text-light);
    background:linear-gradient(
        90deg,
        rgba(122,162,255,0.15),
        transparent
    );
    padding-left:30px;
    text-shadow:0 0 8px rgba(155,182,255,0.6);
}

.dropdown-menu.show{
    display:block;
}

/* ================= ANIMATIONS ================= */
@keyframes shimmer{
    0%{left:-100%}
    50%{left:100%}
    100%{left:100%}
}

@keyframes dropdownFade{
    from{
        opacity:0;
        transform:translateY(-12px) scale(0.96);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}
</style>

<div class="navbar">
    <div class="nav-logo">Event Planner</div>

    <div class="nav-menu">
        <a href="/event-planner-project/index.php">Home</a>

        <div class="dropdown">
            <span class="dropdown-toggle" id="eventToggle">Events ▾</span>
            <div class="dropdown-menu" id="eventMenu">
                <?php
                $res = mysqli_query($conn,"SELECT * FROM events ORDER BY event_name");
                while($row = mysqli_fetch_assoc($res)){
                    echo '<a href="'.$row['page'].'">'.$row['event_name'].'</a>';
                }
                ?>
            </div>
        </div>

        <a href="/event-planner-project/gallery.php">Gallery</a>

        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="/event-planner-project/User/profile.php">Profile</a>
            <a href="/event-planner-project/User/my_bookings.php">My Bookings</a>
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
