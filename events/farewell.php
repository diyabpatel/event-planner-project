<?php
session_start();
include("../db.php");

/* Fetch Farewell Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Farewell Party'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Farewell Party event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Farewell Party</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
background:#0b0f1a;
color:white;
}

/* HERO SECTION */
.hero{
height:100vh;
background:
linear-gradient(rgba(10,10,20,0.75),rgba(10,10,20,0.85)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS CARD */
.overlay{
max-width:700px;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(18px);
padding:60px 50px;
border-radius:25px;
text-align:center;
box-shadow:
0 35px 80px rgba(0,0,0,0.75),
inset 0 0 0 1px rgba(255,255,255,0.18);
animation:fadeUp 1s ease;
}

@keyframes fadeUp{
from{opacity:0;transform:translateY(50px)}
to{opacity:1;transform:none}
}

/* TITLE */
.overlay h1{
font-size:56px;
font-weight:600;
margin-bottom:20px;
letter-spacing:.7px;
background:linear-gradient(90deg,#ffd369,#ffb347,#fff5c3);
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
padding:16px 48px;
border-radius:50px;
font-size:16px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#ffb347,#ffcc33);
color:#1a1a1a;
box-shadow:0 20px 45px rgba(255,204,51,0.5);
transition:.35s;
}

.btn:hover{
transform:translateY(-3px) scale(1.04);
box-shadow:0 28px 60px rgba(255,204,51,0.75);
}

/* RESPONSIVE */
@media(max-width:768px){
.overlay h1{font-size:40px}
.overlay{padding:40px 25px}
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="hero">
    <div class="overlay">
        <h1>Farewell Party</h1>
        <p>
            Celebrate memories, friendships, and unforgettable moments as we bid
            farewell to a beautiful chapter of college life.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Farewell Party
        </a>
    </div>
</div>

</body>
</html>