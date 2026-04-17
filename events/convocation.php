<?php
session_start();
include("../db.php");

$query = "SELECT * FROM events WHERE event_name='Convocation'";
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
<title>Convocation Ceremony</title>

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
background:url("../uploads/images/convocation/c1.jpg") center/cover no-repeat;
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
background:linear-gradient(
to bottom,
rgba(124,58,237,0.15),
rgba(0,0,0,0.25)
);
}

/* FADE UP HERO */
.hero-content{
position:relative;
z-index:2;
color:white;
opacity:0;
transform:translateY(60px);
animation:fadeUp 1s ease forwards;
animation-delay:0.3s;
}

@keyframes fadeUp{
to{
opacity:1;
transform:translateY(0);
}
}

#heroText{
font-size:70px;
font-weight:800;
margin-bottom:10px; /* 🔥 gap control */
}

.hero button{
margin-top:10px; /* pehla 20px hatu */
padding:14px 35px;
border:none;
border-radius:50px;
background:black;
color:white;
font-size:16px;
cursor:pointer;
transition:0.4s;
position:relative;
overflow:hidden;
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

<div class="hero">
<div class="hero-content">
<h1 id="heroText">Convocation Ceremony</h1>

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
<img src="../uploads/images/convocation/convocation_about.jpg">
<div>
<h2>About Convocation</h2>
Convocation Ceremony is one of the most prestigious and formal events in any institution, marking the successful completion of students’ academic journeys. It brings together graduates, faculty, and distinguished guests to celebrate achievements, dedication, and hard work. It serves as a platform to honor academic excellence and recognize the accomplishments of students.

The event typically includes the awarding of degrees, speeches by dignitaries, and formal proceedings that reflect pride and success. It is not just a ceremony but a celebration of growth, perseverance, and the transition from student life to professional life.

Convocation also provides students with a sense of fulfillment and motivation as they step into new opportunities and challenges. With its dignified atmosphere, inspiring moments, and proud celebrations, it becomes a significant and memorable milestone for everyone involved.
</div>
</div>

<!-- SERVICES -->
<div class="services reveal">
<h2>Services Provided</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/convocation/venue.png" onclick="openImage(this.src)"><h3>Venue Setup</h3></div>
<div class="service-card"><img src="../uploads/images/convocation/decoration.png" onclick="openImage(this.src)"><h3>Decoration</h3></div>
<div class="service-card"><img src="../uploads/images/convocation/catering.png" onclick="openImage(this.src)"><h3>Catering</h3></div>
<div class="service-card"><img src="../uploads/images/convocation/degree_distribution.png" onclick="openImage(this.src)"><h3>Degree Distribution</h3></div>
<div class="service-card"><img src="../uploads/images/convocation/photography.png" onclick="openImage(this.src)"><h3>Photography</h3></div>
<div class="service-card"><img src="../uploads/images/convocation/sound.png" onclick="openImage(this.src)"><h3>Sound & Lighting</h3></div>

</div>
</div>

<!-- GALLERY -->
<div class="gallery reveal">
<h2>Our Previous Works</h2>
<div class="service-grid">

<div class="service-card"><img src="../uploads/images/convocation/convocation1.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/convocation/convocation2.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/convocation/convocation3.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/convocation/convocation4.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/convocation/convocation5.png" onclick="openImage(this.src)"></div>
<div class="service-card"><img src="../uploads/images/convocation/convocation6.png" onclick="openImage(this.src)"></div>

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

/* 🔥 PERFECT CENTER FIX */
const cardWidth = cards[0].offsetWidth;
const gap = 30;
const wrapperWidth = document.querySelector(".feedback-wrapper").offsetWidth;

/* 🔥 IMPORTANT FIX */
const totalMove = current * (cardWidth + gap);
const centerOffset = (wrapperWidth / 2) - (cardWidth / 2);

/* 🔥 FINAL */
track.style.transform = `translateX(${centerOffset - totalMove}px)`;
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