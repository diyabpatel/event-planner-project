<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
include("db.php");
?>

<style>
/* ===== NAVBAR BASE ===== */
.navbar{
    height:64px;
    background:#1f4fd8;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 50px;
    position:relative;   /* 🔥 CHANGED */
    z-index:10;          /* 🔥 LOW z-index */
    box-shadow:0 2px 12px rgba(0,0,0,0.15);
}

/* ===== LOGO ===== */
.nav-logo{
    color:#fff;
    font-size:22px;
    font-weight:600;
}

/* ===== LINKS ===== */
.nav-menu{
    display:flex;
    align-items:center;
    gap:26px;
}

.nav-menu a,
.dropdown-toggle{
    color:#fff;
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    cursor:pointer;
}

/* ===== DROPDOWN ===== */
.dropdown{
    position:relative;
}

.dropdown-menu{
    position:absolute;
    top:64px;
    left:0;
    background:#ffffff;
    width:230px;
    border-radius:10px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
    padding:8px 0;
    display:none;
}

.dropdown-menu a{
    display:block;
    padding:11px 18px;
    color:#333;
    text-decoration:none;
}

.dropdown-menu a:hover{
    background:#f2f6ff;
    color:#1f4fd8;
}

.dropdown-menu.show{
    display:block;
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
document.getElementById("eventToggle").onclick = function(e){
    e.stopPropagation();
    document.getElementById("eventMenu").classList.toggle("show");
};
document.onclick = function(){
    document.getElementById("eventMenu").classList.remove("show");
};
</script>
