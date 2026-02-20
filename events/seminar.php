<?php
session_start();
include("../db.php");

/* Fetch Seminar Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Seminar'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Seminar event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seminar</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
background:#0b1020;
color:white;
}

/* HERO SECTION */
.hero{
height:100vh;
background:
linear-gradient(rgba(11,16,32,0.75),rgba(11,16,32,0.9)),
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
border-radius:24px;
text-align:center;
box-shadow:
0 35px 85px rgba(0,0,0,0.8),
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
background:linear-gradient(90deg,#9bb6ff,#c7d2fe,#e0e7ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* TEXT */
.overlay p{
font-size:18px;
line-height:1.8;
opacity:.92;
margin-bottom:40px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:16px 48px;
border-radius:50px;
font-size:16px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#7aa2ff,#4f7cff);
color:white;
box-shadow:0 20px 45px rgba(122,162,255,0.55);
transition:.35s;
}

.btn:hover{
transform:translateY(-3px);
box-shadow:0 28px 60px rgba(122,162,255,0.75);
}

/* RESPONSIVE */
@media(max-width:768px){
.overlay h1{font-size:40px}
.overlay{padding:40px 28px}
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="hero">
    <div class="overlay">
        <h1>Seminar</h1>
        <p>
            Gain insights, expand knowledge, and connect with experts through
            professionally organized academic seminars and talks.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Seminar
        </a>
    </div>
</div>

</body>
</html>