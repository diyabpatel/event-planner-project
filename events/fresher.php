<?php
session_start();
include("../db.php");

/* Fetch Fresher Party Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Fresher Party'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Fresher Party event not found in database");
}

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Freshers Party</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
background:#050814;
color:white;
}

/* HERO SECTION */
.hero{
height:100vh;
background:
linear-gradient(rgba(5,8,20,0.65),rgba(5,8,20,0.85)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS NEON CARD */
.overlay{
max-width:720px;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(20px);
padding:60px 55px;
border-radius:26px;
text-align:center;
box-shadow:
0 40px 90px rgba(0,0,0,0.8),
inset 0 0 0 1px rgba(255,255,255,0.18);
animation:zoomIn 1s ease;
}

@keyframes zoomIn{
from{opacity:0;transform:scale(0.9)}
to{opacity:1;transform:scale(1)}
}

/* TITLE */
.overlay h1{
font-size:58px;
font-weight:600;
margin-bottom:18px;
letter-spacing:.8px;
background:linear-gradient(90deg,#00f2fe,#4facfe,#a18cd1);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* TEXT */
.overlay p{
font-size:18px;
line-height:1.8;
opacity:.9;
margin-bottom:40px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:17px 52px;
border-radius:55px;
font-size:17px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#00f2fe,#4facfe);
color:#021024;
box-shadow:
0 20px 45px rgba(79,172,254,0.6),
0 0 20px rgba(0,242,254,0.6);
transition:.35s;
}

.btn:hover{
transform:translateY(-4px) scale(1.05);
box-shadow:
0 30px 65px rgba(79,172,254,0.85),
0 0 30px rgba(0,242,254,0.85);
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
        <h1>Freshers Party</h1>
        <p>
            A night full of music, dance, laughter, and excitement — 
            welcoming freshers to a vibrant college journey ahead!
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $event_id; ?>" class="btn">
            Book Freshers Party
        </a>
    </div>
</div>

</body>
</html>