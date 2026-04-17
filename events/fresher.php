<?php
session_start();
include("../db.php");

$query = "SELECT * FROM events WHERE event_name='Freshers Party'";
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
<title>Freshers Party</title>

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
background:url("../uploads/images/freshers/fresher_bg.png") center/cover no-repeat;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

/* overlay */
.hero::after{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(rgba(124,58,237,0.2),rgba(0,0,0,0.3));
}

/* ===== FADE-UP HERO CONTENT ===== */
.hero-content{
position:relative;
z-index:2;
font-size:55px;
font-weight:700;
color:white;
text-shadow:0 6px 25px rgba(0,0,0,0.7);

/* 🔥 fade up */
opacity:0;
transform:translateY(60px);
animation:fadeUp 1s ease forwards;
animation-delay:0.3s;
}

/* animation */
@keyframes fadeUp{
0%{
opacity:0;
transform:translateY(60px);
}
100%{
opacity:1;
transform:translateY(0);
}
}

/* BUTTON */
.hero button{
margin-top:20px;
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

/* ===== FEEDBACK SQUARE PERFECT ===== */

.feedback-wrapper{
width:100%;
display:flex;
justify-content:center;
align-items:center;
overflow:hidden;
position:relative;
height:320px;
}

.feedback-track{
display:flex;
align-items:center;
gap:25px;
transition:transform 0.6s ease;
}

/* 🔥 SQUARE CARD */
.feedback-card{
width:280px;
height:280px;              /* 🔥 square */
padding:20px;
border-radius:20px;
background:white;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.1);

display:flex;
flex-direction:column;
justify-content:space-between;

opacity:0.4;
transform:scale(0.9);
transition:0.5s;
}

/* ACTIVE CENTER */
.feedback-card.active{
opacity:1;
transform:scale(1.05);
box-shadow:0 20px 50px rgba(124,58,237,0.3);
}

/* ⭐ rating */
.feedback-card{
font-size:14px;
}

/* 🔥 TEXT 3 LINE LIMIT */
.feedback-card p{
display:-webkit-box;
-webkit-line-clamp:3;
-webkit-box-orient:vertical;

overflow:hidden;
text-overflow:ellipsis;

line-height:1.5;
font-size:14px;
}

/* NAME */
.feedback-card b{
font-size:14px;
color:#2d1b69;
}

/* DOTS */
.dots{
margin-top:20px;
text-align:center;
}

.dot{
height:8px;
width:8px;
margin:5px;
background:#ccc;
border-radius:50%;
display:inline-block;
}

.dot.active{
background:#7c3aed;
transform:scale(1.2);
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
Freshers Party
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
<img src="../uploads/images/freshers/fresher_about.png">
<div>
<h2>About Freshers Party</h2>
Freshers Party is one of the most exciting and welcoming events in any institution, designed to greet new students and make them feel comfortable in their new environment. It brings together freshers, seniors, and faculty for a day filled with fun, interaction, and new beginnings. It serves as a platform for students to introduce themselves, build connections, and start their college journey with confidence.

The event typically includes a variety of activities such as dance performances, music, fashion shows, games, and interactive sessions that create a lively and energetic atmosphere. It is not just a party but a celebration of new friendships, creativity, and youthful enthusiasm within the institution.

Freshers Party also helps students overcome hesitation, express their personalities, and create their first set of memorable experiences in college life. With vibrant decorations, engaging performances, and active participation, it becomes a joyful and unforgettable occasion for everyone involved.
</div>
</div>

<!-- SERVICES -->
<div class="services reveal">
<h2>Services Provided</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/freshers/venue.png" onclick="openImage(this.src)"><h3>Venue Setup</h3></div>
<div class="service-card"><img src="../uploads/images/freshers/catering.png" onclick="openImage(this.src)"><h3>Catering</h3></div>
<div class="service-card"><img src="../uploads/images/freshers/sound.png" onclick="openImage(this.src)"><h3>DJ Night</h3></div>
<div class="service-card"><img src="../uploads/images/freshers/decoration.png" onclick="openImage(this.src)"><h3>Decoration</h3></div>
<div class="service-card"><img src="../uploads/images/freshers/photography.png" onclick="openImage(this.src)"><h3>Photography</h3></div>
<div class="service-card"><img src="../uploads/images/freshers/videography.png" onclick="openImage(this.src)"><h3>Videography</h3></div>

</div>
</div>

<!-- GALLERY -->
<div class="gallery reveal">
<h2>Our Previous Works</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/freshers/fresher1.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/freshers/fresher2.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/freshers/fresher3.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/freshers/fresher4.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/freshers/fresher5.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/freshers/fresher6.png" onclick="openImage(this.src)"></div>

</div>
</div>

<!-- FEEDBACK -->

<div class="feedback-section reveal">
<h2>What Our Customers Say</h2>

<div class="feedback-wrapper">
<div class="feedback-track">

<?php
while($f=mysqli_fetch_assoc($feedback_query)){
echo "<div class='feedback-card'>
⭐ ".$f['rating']."
<p>".$f['comment']."</p>
<b>".$f['college_name']."</b>
</div>";
}
?>

</div>
</div>
<div class="dots" id="dots"></div>
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

/* FEEDBACK SLIDER */
let current = 0;
const cards = document.querySelectorAll(".feedback-card");
const track = document.querySelector(".feedback-track");
const dotsContainer = document.getElementById("dots");

cards.forEach((_,i)=>{
let dot = document.createElement("span");
dot.classList.add("dot");
if(i===0) dot.classList.add("active");
dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".dot");

function updateCarousel(){

cards.forEach(card => card.classList.remove("active"));
dots.forEach(dot => dot.classList.remove("active"));

cards[current].classList.add("active");
dots[current].classList.add("active");

const offset = current * 310;
track.style.transform = `translateX(calc(50% - ${offset}px - 140px))`;
}

updateCarousel();

setInterval(()=>{
current++;
if(current >= cards.length){
current = 0;
}
updateCarousel();
},2500);

</script>

</body>
<?php include("../footer.php"); ?>
</html>
