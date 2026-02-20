<?php
session_start();
include("../db.php");

/* Fetch Sports Day Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Sports Day'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Sports Day event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sports Day</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
background:#040812;
color:white;
}

/* HERO */
.hero{
height:100vh;
background:
linear-gradient(rgba(4,8,18,0.65),rgba(4,8,18,0.9)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS CARD */
.overlay{
max-width:750px;
background:rgba(255,255,255,0.10);
backdrop-filter:blur(18px);
padding:65px 60px;
border-radius:28px;
text-align:center;
box-shadow:
0 40px 95px rgba(0,0,0,0.85),
inset 0 0 0 1px rgba(255,255,255,0.15);
animation:slideUp 1s ease;
}

@keyframes slideUp{
from{opacity:0;transform:translateY(60px)}
to{opacity:1;transform:none}
}

/* TITLE */
.overlay h1{
font-size:60px;
font-weight:700;
margin-bottom:20px;
letter-spacing:1px;
background:linear-gradient(90deg,#00ff87,#60efff,#00ff87);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* TEXT */
.overlay p{
font-size:18px;
line-height:1.9;
opacity:.92;
margin-bottom:42px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:18px 56px;
border-radius:60px;
font-size:17px;
font-weight:600;
letter-spacing:.4px;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#00ff87,#00c853);
color:#03210f;
box-shadow:
0 22px 50px rgba(0,255,135,0.55),
0 0 25px rgba(0,255,135,0.6);
transition:.35s;
}

.btn:hover{
transform:translateY(-4px) scale(1.06);
box-shadow:
0 32px 70px rgba(0,255,135,0.85),
0 0 40px rgba(0,255,135,0.85);
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
        <h1>Sports Day</h1>
        <p>
            Celebrate strength, teamwork, and competitive spirit with thrilling
            athletic events, games, and sportsmanship-filled moments.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Sports Day
        </a>
    </div>
</div>

</body>
</html>