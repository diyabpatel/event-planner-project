<?php
session_start();
include("../db.php");

$query = "SELECT * FROM events WHERE event_name='Farewell Party'";
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
<title>Farewell Party</title>

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
background:url("../uploads/images/farewell/farewell_bg.png") center/cover no-repeat;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

.hero::after{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(rgba(124,58,237,0.2),rgba(0,0,0,0.3));
}

.hero-content{
position:relative;
z-index:2;
font-size:55px;
font-weight:700;
color:white;
text-shadow:0 6px 25px rgba(0,0,0,0.7);
}

.hero button{
margin-top:25px;
padding:15px 35px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#000,#1f1f1f);
color:white;
font-size:18px;
cursor:pointer;
transition:0.4s;
}

.hero button:hover{
transform:translateY(-5px) scale(1.05);
}

/* ===== CONTAINER ===== */
.container{
padding:80px;
}

/* ===== REVEAL ===== */
.reveal{
opacity:0;
transform:translateY(60px);
transition:1s;
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
}

/* ===== SERVICES ===== */
.services{
margin-top:80px;
}

.service-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px; /* reduced gap */
}

.service-card{
background:white;
padding:15px; /* reduced padding */
border-radius:20px;
text-align:center;
box-shadow:0 10px 25px rgba(124,58,237,0.12);
transition:0.4s;
opacity:0;
transform:translateY(40px);
}

.reveal.active .service-card{
opacity:1;
transform:translateY(0);
}

.service-card:hover{
transform:translateY(-8px);
box-shadow:0 20px 45px rgba(124,58,237,0.25);
}

/* smaller text */
.service-card h3{
font-size:16px;
margin-top:10px;
}

/* ===== IMAGE STYLE (SQUARE + PREMIUM) ===== */
.service-card img,
.gallery-grid img{
width:100%;
aspect-ratio:1/1;
object-fit:cover;
border-radius:14px;
transition:0.4s ease;
cursor:pointer;
}

/* premium hover */
.service-card img:hover,
.gallery-grid img:hover{
transform:scale(1.06);
box-shadow:0 15px 35px rgba(124,58,237,0.35);
filter:brightness(1.08);
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
gap:25px;
}

.gallery-card{
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 10px 25px rgba(124,58,237,0.12);
transition:0.4s;
}

.gallery-card img{
width:100%;
aspect-ratio:1/1;
object-fit:cover;
}

.gallery-card:hover{
transform:translateY(-10px);
box-shadow:0 20px 50px rgba(124,58,237,0.35);
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
}

/* ===== FOOTER ===== */
footer{
background:#2d1b69;
color:white;
padding:40px;
margin-top:80px;
text-align:center;
}

</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->
<div class="hero">
<div class="hero-content">
Farewell Party
<br>
<?php
if(isset($_SESSION['user_id'])){
echo "<button onclick=\"location.href='../user/book_event.php?event_id=$event_id'\">Book Now</button>";
}else{
echo "<button onclick=\"location.href='../login.php'\">Login to Book</button>";
}
?>
</div>
</div>

<div class="container">

<!-- ABOUT -->
<div class="about reveal">
<img src="../uploads/images/farewell/farewell_about.png">
<div>
<h2>About Farewell</h2>
Farewell Party is one of the most emotional and memorable events in any institution, organized to bid goodbye to seniors as they move on to the next phase of their lives. It brings together students and faculty to celebrate the journey, achievements, and memories created over the years. It serves as a platform to express gratitude, appreciation, and best wishes for the future.
The event typically includes heartfelt speeches, performances, music, and fun activities that reflect both joy and nostalgia. It is not just a goodbye event but a celebration of friendships, shared experiences, and the strong bonds formed during college life.
Farewell Party also gives students an opportunity to cherish their final moments together, relive memories, and create lasting impressions. With emotional moments, lively performances, and enthusiastic participation, it becomes a deeply meaningful and unforgettable experience for everyone involved.
</div>
</div>
<!-- SERVICES -->
<div class="services reveal">
<h2>Services Provided</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/farewell/venue.png" onclick="openImage(this.src)"><h3>Venue Setup</h3></div>
<div class="service-card"><img src="../uploads/images/farewell/decoration.png" onclick="openImage(this.src)"><h3>Decoration</h3></div>
<div class="service-card"><img src="../uploads/images/farewell/catering.png" onclick="openImage(this.src)"><h3>Catering</h3></div>
<div class="service-card"><img src="../uploads/images/farewell/dance_performance.png" onclick="openImage(this.src)"><h3>Dance Performances</h3></div>
<div class="service-card"><img src="../uploads/images/farewell/photography.png" onclick="openImage(this.src)"><h3>Photography</h3></div>
<div class="service-card"><img src="../uploads/images/farewell/sound.png" onclick="openImage(this.src)"><h3>Sound & Lighting</h3></div>

</div>
</div>

<!-- GALLERY -->
<div class="gallery reveal">
<h2>Our Previous Works</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/farewell/farewell1.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/farewell/farewell2.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/farewell/farewell3.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/farewell/farewell4.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/farewell/farewell5.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/farewell/farewell6.png" onclick="openImage(this.src)"></div>

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

<!-- IMAGE MODAL -->
<div id="imgModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);justify-content:center;align-items:center;">
<span onclick="closeImage()" style="position:absolute;top:30px;right:40px;font-size:40px;color:white;cursor:pointer;">&times;</span>
<img id="modalImg" style="max-width:90%;max-height:90%;border-radius:15px;">
</div>

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

function openImage(src){
document.getElementById("imgModal").style.display="flex";
document.getElementById("modalImg").src = src;
}

function closeImage(){
document.getElementById("imgModal").style.display="none";
}
</script>

</body>
<?php include("../footer.php"); ?>
</html>