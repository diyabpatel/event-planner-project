<?php
session_start();
include("../db.php");

$query = "SELECT * FROM events WHERE event_name='Annual Day'";
$result = mysqli_query($conn,$query);
$event = mysqli_fetch_assoc($result);
$event_id = $event['event_id'];

$feedback_query = mysqli_query($conn,"
SELECT f.rating,f.comment,u.college_name
FROM feedback f
JOIN bookings b ON f.booking_id=b.booking_id
JOIN users u ON f.user_id=u.user_id
WHERE b.event_id='$event_id'
ORDER BY f.created_at DESC
LIMIT 6
");

if(!$feedback_query){
$feedback_error = mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Annual Day Event</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>


/* ===== GLOBAL ===== */
body{
margin:0;
font-family:'Poppins',sans-serif;
background:#f9f7ff;
color:#2d1b69;
scroll-behavior:smooth;
}

/* ===== HERO ===== */
.hero{
position:relative;
height:700px;
background:url("../uploads/images/annual/annualday_bg03.jpg") center/cover no-repeat;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

/* ✅ CLEAR IMAGE + LIGHT GRADIENT */
.hero::after{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(
to bottom,
rgba(124,58,237,0.15),
rgba(0,0,0,0.25)
);
}

/* TEXT IMPROVE */
.hero-content{
position:relative;
z-index:2;
font-size:55px;
font-weight:700;
color:white;
text-shadow:0 6px 25px rgba(0,0,0,0.7);
animation:fadeUp 1s ease;
}

/* BUTTON PREMIUM */
/* BUTTON PREMIUM (UPDATED BLACK) */
.hero button{
margin-top:25px;
padding:15px 35px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#000000,#1f1f1f);
color:white;
font-size:18px;
cursor:pointer;
box-shadow:0 10px 30px rgba(0,0,0,0.6);
transition:0.4s;
}

.hero button:hover{
transform:translateY(-5px) scale(1.05);
box-shadow:0 15px 40px rgba(0,0,0,0.9);
background:linear-gradient(135deg,#111,#333);
}

/* ===== CONTAINER ===== */
.container{
padding:80px;
}

/* ===== REVEAL ===== */
.reveal{
opacity:0;
transform:translateY(60px);
transition:all 1s ease;
}

.reveal.active{
opacity:1;
transform:translateY(0);
}

/* ===== ABOUT ===== */
.about{
display:flex;
gap:50px;
align-items:center;
background:white;
padding:40px;
border-radius:20px;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
}

.about img{
width:500px;
border-radius:20px;
transition:0.4s;
}

.about img:hover{
transform:scale(1.05);
}

/* ===== SERVICES ===== */
.services{
margin-top:80px;
}

.service-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:30px;
}

.service-card{
background:white;
padding:25px;
border-radius:20px;
text-align:center;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
transition:0.4s;
opacity:0;
transform:translateY(40px);
}

.reveal.active .service-card{
opacity:1;
transform:translateY(0);
}

.service-card:hover{
transform:translateY(-10px);
box-shadow:0 15px 40px rgba(124,58,237,0.3);
}

.service-card img{
width:100%;
height:200px;
object-fit:cover;
border-radius:15px;
}

/* stagger */
.service-card:nth-child(1){transition-delay:0.1s;}
.service-card:nth-child(2){transition-delay:0.2s;}
.service-card:nth-child(3){transition-delay:0.3s;}
.service-card:nth-child(4){transition-delay:0.4s;}
.service-card:nth-child(5){transition-delay:0.5s;}
.service-card:nth-child(6){transition-delay:0.6s;}

/* ===== GALLERY ===== */
.gallery{
margin-top:80px;
}

.gallery-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.gallery-grid img{
width:100%;
height:220px;
object-fit:cover;
border-radius:15px;
transition:0.4s;
}

.gallery-grid img:hover{
transform:scale(1.08);
box-shadow:0 10px 30px rgba(124,58,237,0.3);
}

/* ===== FEEDBACK ===== */
.feedback-section{
margin-top:100px;
text-align:center;
}

.feedback-grid{
margin-top:40px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

.feedback-card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 10px 30px rgba(124,58,237,0.15);
transition:0.4s;
}

.feedback-card:hover{
transform:translateY(-10px);
}

/* ===== FOOTER ===== */
footer{
background:#2d1b69;
color:white;
padding:40px;
margin-top:80px;
text-align:center;
}

/* ===== ANIMATION ===== */
@keyframes fadeUp{
from{
opacity:0;
transform:translateY(30px);
}
to{
opacity:1;
transform:translateY(0);
}
}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->
<div class="hero">
<div class="hero-content">
Annual Day Event
<br>
<?php
if(isset($_SESSION['user_id']))
{
echo "<button onclick=\"location.href='../user/book_event.php?event_id=$event_id'\">Book Now</button>";
}
else
{
echo "<button onclick=\"location.href='../login.php'\">Login to Book</button>";
}
?>
</div>
</div>

<div class="container">

<!-- ABOUT -->
<div class="about reveal">
<img src="../uploads/images/annual/annualday_about.jpg">
<div>
<h2>About Annual Day</h2>
<p>Annual Day is the biggest celebration event...</p>
</div>
</div>

<!-- SERVICES -->
<div class="services reveal">
<h2>Services Provided</h2>
<div class="service-grid">
<div class="service-card"><img src="../uploads/images/annual/venue.png"><h3>Venue Setup</h3></div>
<div class="service-card"><img src="../uploads/images/annual/decoration.png"><h3>Decoration</h3></div>
<div class="service-card"><img src="../uploads/images/annual/catering.png"><h3>Catering</h3></div>
<div class="service-card"><img src="../uploads/images/annual/photography.png"><h3>Photography</h3></div>
<div class="service-card"><img src="../uploads/images/annual/videography.jpg"><h3>Videography</h3></div>
<div class="service-card"><img src="../uploads/images/annual/sound.png"><h3>Sound & Lighting</h3></div>
</div>
</div>

<!-- GALLERY -->
<div class="gallery reveal">
<h2>Our Previous Works</h2>
<div class="gallery-grid">
<img src="../uploads/images/annual/image1.webp">
<img src="../uploads/images/annual/image2.jpg">
<img src="../uploads/images/annual/image3.jpg">
<img src="../uploads/images/annual/image4.jpg">
<img src="../uploads/images/annual/image5.jpg">
<img src="../uploads/images/annual/image6.jpg">
</div>
</div>

<!-- FEEDBACK -->
<div class="feedback-section reveal">
<h2>What Our Customers Say</h2>
<div class="feedback-grid">

<?php
if(isset($feedback_error)){
echo "<p>$feedback_error</p>";
}
else if(mysqli_num_rows($feedback_query)>0){
while($f=mysqli_fetch_assoc($feedback_query)){
$stars = str_repeat("⭐",$f['rating']);
echo "<div class='feedback-card'>
<div>$stars</div>
<p>".$f['comment']."</p>
<b>".$f['college_name']."</b>
</div>";
}
}else{
echo "<p>No feedback available yet.</p>";
}
?>

</div>
</div>

</div>

<footer>
Event Management System
</footer>

<!-- SCROLL JS -->
<script>
window.addEventListener("scroll", function(){
let reveals = document.querySelectorAll(".reveal");
for(let i=0;i<reveals.length;i++){
let windowHeight = window.innerHeight;
let elementTop = reveals[i].getBoundingClientRect().top;
if(elementTop < windowHeight - 100){
reveals[i].classList.add("active");
}
}
});
</script>

</body>
</html>