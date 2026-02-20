<?php
session_start();
include("../db.php");

/* Fetch Cultural Fest Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Cultural Fest'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Cultural Fest event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cultural Fest</title>

<!-- Stylish font -->
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
linear-gradient(rgba(11,16,32,0.65),rgba(11,16,32,0.85)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS OVERLAY */
.overlay{
max-width:680px;
background:rgba(255,255,255,0.14);
backdrop-filter:blur(20px);
padding:55px 48px;
border-radius:24px;
text-align:center;
box-shadow:
0 30px 75px rgba(0,0,0,0.75),
inset 0 0 0 1px rgba(255,255,255,0.18);
animation:fadeUp 1s ease;
}

@keyframes fadeUp{
from{opacity:0;transform:translateY(45px)}
to{opacity:1;transform:none}
}

.overlay h1{
font-size:54px;
font-weight:600;
margin-bottom:18px;
letter-spacing:.6px;
background:linear-gradient(90deg,#ff9ff3,#feca57,#ff9f43);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.overlay p{
font-size:18px;
line-height:1.75;
opacity:.92;
margin-bottom:36px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:15px 44px;
border-radius:45px;
font-size:16px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#ff6b6b,#ff4757,#ff9f43);
color:white;
box-shadow:0 18px 40px rgba(255,107,107,0.6);
transition:.35s;
}

.btn:hover{
transform:translateY(-3px) scale(1.03);
box-shadow:0 26px 55px rgba(255,107,107,0.85);
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
        <h1>Cultural Fest</h1>
        <p>
            Dive into a vibrant celebration of music, dance, drama, art,
            and creativity — where talent meets culture and memories are made.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Cultural Fest
        </a>
    </div>
</div>

</body>
</html>