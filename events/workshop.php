<?php
session_start();
include("../db.php");

/* Fetch Workshop Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Workshop'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Workshop event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Workshop</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
background:#050814;
color:white;
}

/* HERO */
.hero{
height:100vh;
background:
linear-gradient(rgba(5,8,20,0.7),rgba(5,8,20,0.9)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS CARD */
.overlay{
max-width:720px;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(18px);
padding:60px 55px;
border-radius:26px;
text-align:center;
box-shadow:
0 38px 90px rgba(0,0,0,0.85),
inset 0 0 0 1px rgba(255,255,255,0.18);
animation:fadeUp 1s ease;
}

@keyframes fadeUp{
from{opacity:0;transform:translateY(45px)}
to{opacity:1;transform:none}
}

/* TITLE */
.overlay h1{
font-size:56px;
font-weight:600;
margin-bottom:18px;
letter-spacing:.6px;
background:linear-gradient(90deg,#38ef7d,#11998e,#38ef7d);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* TEXT */
.overlay p{
font-size:18px;
line-height:1.8;
opacity:.92;
margin-bottom:38px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:17px 52px;
border-radius:55px;
font-size:16px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#38ef7d,#11998e);
color:#031b12;
box-shadow:
0 22px 50px rgba(56,239,125,0.55),
0 0 25px rgba(56,239,125,0.5);
transition:.35s;
}

.btn:hover{
transform:translateY(-3px) scale(1.05);
box-shadow:
0 32px 70px rgba(56,239,125,0.8),
0 0 35px rgba(56,239,125,0.8);
}

/* RESPONSIVE */
@media(max-width:768px){
.overlay h1{font-size:42px}
.overlay{padding:40px 28px}
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="hero">
    <div class="overlay">
        <h1>Workshop</h1>
        <p>
            Learn by doing through hands-on workshops designed to build skills,
            encourage innovation, and spark creativity among students.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Workshop
        </a>
    </div>
</div>

</body>
</html>