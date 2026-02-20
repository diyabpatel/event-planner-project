<?php
session_start();
include("../db.php");

/* Fetch Convocation Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Convocation'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("Convocation event not found in database");
}

$convocation_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Convocation Ceremony</title>

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
linear-gradient(rgba(11,16,32,0.75),rgba(11,16,32,0.85)),
url('../uploads/images/bg.jpg') no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
padding:0 8%;
}

/* GLASS CONTENT */
.overlay{
max-width:650px;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(18px);
padding:55px 45px;
border-radius:22px;
text-align:center;
box-shadow:
0 30px 70px rgba(0,0,0,0.75),
inset 0 0 0 1px rgba(255,255,255,0.15);
animation:fadeUp 1s ease;
}

@keyframes fadeUp{
from{opacity:0;transform:translateY(40px)}
to{opacity:1;transform:none}
}

.overlay h1{
font-size:52px;
font-weight:600;
margin-bottom:18px;
letter-spacing:.5px;
background:linear-gradient(90deg,#ffeaa7,#ffffff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.overlay p{
font-size:17px;
line-height:1.7;
opacity:.9;
margin-bottom:34px;
}

/* BUTTON */
.btn{
display:inline-block;
padding:15px 42px;
border-radius:40px;
font-size:16px;
font-weight:500;
text-decoration:none;
cursor:pointer;
background:linear-gradient(135deg,#ff7675,#ff4757);
color:white;
box-shadow:0 18px 40px rgba(255,118,117,0.6);
transition:.35s;
}

.btn:hover{
transform:translateY(-2px);
box-shadow:0 24px 50px rgba(255,118,117,0.8);
}

/* RESPONSIVE */
@media(max-width:768px){
.overlay h1{font-size:38px}
.overlay{padding:40px 28px}
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="hero">
    <div class="overlay">
        <h1>Convocation Ceremony</h1>
        <p>
            Celebrate academic excellence and honor graduates in a prestigious
            convocation ceremony filled with pride, tradition, and memorable moments.
        </p>

        <a href="../User/book_event.php?event_id=<?php echo $convocation_id; ?>" class="btn">
            Book Convocation Event
        </a>
    </div>
</div>

</body>
</html>