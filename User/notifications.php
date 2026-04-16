<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== FETCH NOTIFICATIONS ===== */

$query = mysqli_query($conn,"
SELECT booking_id, notification, is_read, booking_date, payment_status
FROM bookings
WHERE user_id='$user_id' 
AND notification IS NOT NULL 
AND notification!=''
ORDER BY booking_id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications</title>

<style>

/* ===== BODY ===== */
body{
margin:0;
font-family:'Segoe UI',sans-serif;

/* 🔥 ANIMATED PURPLE BACKGROUND */
background:linear-gradient(270deg,#f5f3ff,#ede9fe,#e0e7ff,#f3e8ff);
background-size:400% 400%;
animation:bgMove 12s ease infinite;

color:#2d2d3a;
}

@keyframes bgMove{
0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}
}

/* ===== CONTAINER ===== */
.container{
max-width:820px;
margin:0 auto;
padding:30px 20px;
animation:fadeIn 0.8s ease;
}

/* PAGE ENTRY */
@keyframes fadeIn{
from{
opacity:0;
transform:translateY(20px);
}
to{
opacity:1;
transform:translateY(0);
}
}

/* ===== TITLE ===== */
h2{
text-align:center;
margin-bottom:35px;
font-size:34px;
font-weight:800;

background:linear-gradient(90deg,#7c3aed,#a78bfa,#c4b5fd);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}
/* ===== CARD ===== */
.card{
background:rgba(255,255,255,0.7);
backdrop-filter:blur(25px);
padding:22px;
margin-bottom:22px;
border-radius:18px;
position:relative;
overflow:hidden;

/* BORDER + SHADOW */
border:1px solid rgba(255,255,255,0.6);
box-shadow:0 10px 30px rgba(124,58,237,0.15);

transition:0.5s ease;
}

/* ✨ SHINE PASS EFFECT */
.card::after{
content:"";
position:absolute;
top:-50%;
left:-50%;
width:200%;
height:200%;
background:linear-gradient(
120deg,
transparent,
rgba(255,255,255,0.7),
transparent
);
transform:rotate(25deg);
opacity:0;
transition:0.7s;
}

/* 🔥 GLOW BORDER */
.card::before{
content:"";
position:absolute;
inset:0;
border-radius:18px;
padding:1px;
background:linear-gradient(135deg,#7c3aed,#a78bfa,#c4b5fd);
-webkit-mask:
linear-gradient(#fff 0 0) content-box,
linear-gradient(#fff 0 0);
-webkit-mask-composite:xor;
mask-composite:exclude;
opacity:0;
transition:0.4s;
}

/* 🚀 HOVER */
.card:hover{
transform:translateY(-10px) scale(1.03);

box-shadow:
0 20px 60px rgba(124,58,237,0.35),
0 0 40px rgba(167,139,250,0.25);
}

/* ACTIVATE EFFECTS */
.card:hover::before{
opacity:1;
}

.card:hover::after{
left:100%;
top:100%;
opacity:1;
}

/* UNREAD */
.card.unread{
background:linear-gradient(135deg,#faf5ff,#ede9fe);
border-left:6px solid #7c3aed;
}

/* ===== TEXT ===== */
.msg{
font-size:15.5px;
margin-bottom:8px;
line-height:1.6;
}

.time{
font-size:12px;
color:#6b7280;
margin-top:5px;
}

/* ===== TAG ===== */
.tag{
display:inline-block;
margin-top:10px;
padding:5px 12px;
border-radius:50px;
font-size:11px;
font-weight:600;
letter-spacing:0.3px;
backdrop-filter:blur(10px);
}

/* CONFIRMED */
.confirmed{
background:linear-gradient(135deg,#22c55e,#4ade80);
color:white;
box-shadow:0 0 12px rgba(34,197,94,0.5);
}

/* REJECTED */
.rejected{
background:linear-gradient(135deg,#ef4444,#f87171);
color:white;
box-shadow:0 0 12px rgba(239,68,68,0.5);
}

/* ===== BUTTON ===== */
.btn{
display:inline-block;
margin-top:14px;
padding:10px 20px;
background:linear-gradient(135deg,#7c3aed,#a78bfa);
color:white;
text-decoration:none;
border-radius:12px;
font-size:13px;
font-weight:600;
position:relative;
overflow:hidden;
transition:0.4s;

box-shadow:0 8px 25px rgba(124,58,237,0.5);
}

/* ✨ BUTTON SHINE */
.btn::after{
content:"";
position:absolute;
top:0;
left:-100%;
width:100%;
height:100%;
background:linear-gradient(
120deg,
transparent,
rgba(255,255,255,0.7),
transparent
);
transition:0.5s;
}

/* 🔥 BUTTON HOVER */
.btn:hover{
transform:scale(1.08);
box-shadow:0 15px 40px rgba(124,58,237,0.7);
}

/* MOVE SHINE */
.btn:hover::after{
left:100%;
}

/* ===== EMPTY ===== */
.empty{
text-align:center;
margin-top:80px;
color:#6b7280;
font-size:16px;
}

</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>Notifications</h2>

<?php
if(mysqli_num_rows($query) > 0){

    $ids = [];

    while($row = mysqli_fetch_assoc($query)){

        /* collect unread ids */
        if($row['is_read'] == 0){
            $ids[] = $row['booking_id'];
        }

        $class = ($row['is_read'] == 0) ? "card unread" : "card";

        echo "<div class='$class'>";

        /* MESSAGE */
        echo "<div class='msg'>".$row['notification']."</div>";

        /* STATUS TAG */
        if($row['payment_status'] == "Rejected"){
            echo "<span class='tag rejected'>Action Required</span>";
        } else {
            echo "<span class='tag confirmed'>Confirmed</span>";
        }

        /* TIME */
        echo "<div class='time'>".$row['booking_date']."</div>";

        /* ONLY SHOW BUTTON FOR REJECTED */
        if($row['payment_status'] == "Rejected"){
            echo "
            <a href='reupload_payment.php?booking_id=".$row['booking_id']."' class='btn'>
                Re-upload Documents
            </a>
            ";
        }

        echo "</div>";
    }

    /* MARK AS READ */
    if(!empty($ids)){
        $id_list = implode(",", $ids);

        mysqli_query($conn,"
        UPDATE bookings 
        SET is_read=1 
        WHERE booking_id IN ($id_list)
        ");
    }

}else{
    echo "<div class='empty'>No notifications yet</div>";
}
?>

</div>

</body>
<?php include("footer.php"); ?>
</html>