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
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Annual Day Event</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:#f9f7ff;
color:#2d1b69;
}

.hero{
height:750px; /* 800 ne 750 kar */
background:url("../uploads/images/annual/annualday_bg03.jpg") center center/cover no-repeat;
/* 👆 important: center center */
display:flex;
justify-content:center;
align-items:center;
text-align:center;
position:relative;
}
.hero::after{
content:"";
position:absolute;
width:100%;
height:100%;
background:rgba(0,0,0,0.3);
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
font-size:55px;
font-weight:700;
}

.hero button{
margin-top:20px;
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
transform:translateY(-4px) scale(1.05);
box-shadow:0 15px 40px rgba(124,58,237,0.6);
}

/* CONTAINER */
.container{padding:80px;}

/* ABOUT */
.about{
display:flex;
gap:50px;
background:white;
padding:40px;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);

opacity:0;
transform:translateY(80px);
transition:1s;
}

.about.active{
opacity:1;
transform:translateY(0);
}

.about img{
width:500px;
border-radius:20px;
transition:0.5s;
}

.about:hover img{
transform:scale(1.05);
}

/* TEXT STYLE */
#typingText{
line-height:1.8;
background: linear-gradient(90deg,#7c3aed,#4f46e5,#9333ea);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* GRID */
.service-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

/* ===== ONE BY ONE ANIMATION ===== */
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
transform:translateY(0) scale(1);
}

/* STAGGER DELAY */
.service-card:nth-child(1){transition-delay:0.1s;}
.service-card:nth-child(2){transition-delay:0.3s;}
.service-card:nth-child(3){transition-delay:0.5s;}
.service-card:nth-child(4){transition-delay:0.7s;}
.service-card:nth-child(5){transition-delay:0.9s;}
.service-card:nth-child(6){transition-delay:1.1s;}

.service-card:hover{
transform:translateY(-8px) scale(1.03);
box-shadow:0 20px 40px rgba(124,58,237,0.25);
}

.service-card img{
width:100%;
aspect-ratio:1/1;
object-fit:cover;
border-radius:10px;
transition:0.4s;
}

.service-card img:hover{
transform:scale(1.06);
}

/* ===== GALLERY SAME EFFECT ===== */
.gallery-card{
opacity:0;
transform:translateY(60px) scale(0.95);
transition:all 0.6s ease;
}

.reveal.active .gallery-card{
opacity:1;
transform:translateY(0) scale(1);
}

.gallery-card:nth-child(1){transition-delay:0.1s;}
.gallery-card:nth-child(2){transition-delay:0.3s;}
.gallery-card:nth-child(3){transition-delay:0.5s;}
.gallery-card:nth-child(4){transition-delay:0.7s;}
.gallery-card:nth-child(5){transition-delay:0.9s;}
.gallery-card:nth-child(6){transition-delay:1.1s;}

/* 🔥 RECTANGLE CARD (PERFECT LOOK) */
.feedback-card{
width:320px;
height:220px;
padding:20px;
border-radius:20px;

background:linear-gradient(135deg,#ffffff,#f3f0ff);
border:1px solid rgba(124,58,237,0.1);

text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.1);

display:flex;
flex-direction:column;
justify-content:space-between;

opacity:0.4;
transform:scale(0.9);
transition:0.5s;
}


.feedback-wrapper{
width:100%;
display:flex;
justify-content:center;
align-items:center;
overflow:hidden;
position:relative;
height:260px;
}

.feedback-track{
display:flex;
align-items:center;
gap:25px;
transition:transform 0.6s ease;
}
/* TEXT PERFECT FIT */
.feedback-card p{
display:-webkit-box;
-webkit-line-clamp:3;
-webkit-box-orient:vertical;

overflow:hidden;
text-overflow:ellipsis;

line-height:1.5;
font-size:14px;
}

/* CENTER ACTIVE */
.feedback-card.active{
opacity:1;
transform:scale(1.05);
box-shadow:0 20px 50px rgba(124,58,237,0.3);
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
/* REVEAL */
.reveal{
opacity:0;
transform:translateY(50px);
transition:0.8s;
}

.reveal.active{
opacity:1;
transform:translateY(0);
}


</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->

<div class="hero">
<div class="hero-content">
<h1 id="heroText">Annual Day Event</h1>

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
<img src="../uploads/images/annual/annualday_about.png">
<div>
<h2>About Annual Day</h2>
<p id="typingText">
Annual Day is one of the most anticipated and grand celebrations in any institution, bringing together students, faculty, and guests for a day filled with joy, creativity, and achievement. It is not just an event, but a reflection of the entire year’s hard work, dedication, and talent.

This special occasion provides a vibrant platform for students to showcase their skills through cultural performances such as dance, music, drama, and artistic presentations. Every performance tells a story, highlighting the passion and creativity of students.

The event also includes prestigious award ceremonies that recognize academic excellence, extracurricular achievements, and outstanding contributions. It boosts confidence and motivates students to strive for greater success in the future.

</p>
</div>
</div>
<br><br>


<!-- SERVICES -->

<div class="reveal">
<h2>Services</h2>
<div class="service-grid">
<div class="service-card"><img src="../uploads/images/annual/venue.PNG" onclick="openImage(this.src)"><h3>Venue Setup</h3></div>
<div class="service-card"><img src="../uploads/images/annual/decoration.PNG" onclick="openImage(this.src)"><h3>Decoration</h3></div>
<div class="service-card"><img src="../uploads/images/annual/catering.PNG" onclick="openImage(this.src)"><h3>Catering</h3></div>
<div class="service-card"><img src="../uploads/images/annual/photography.PNG" onclick="openImage(this.src)"><h3>Photography</h3></div>
<div class="service-card"><img src="../uploads/images/annual/videography.PNG" onclick="openImage(this.src)"><h3>Videography</h3></div>
<div class="service-card"><img src="../uploads/images/annual/sound.PNG" onclick="openImage(this.src)"><h3>Sound System</h3></div>
</div>
</div>

<!-- GALLERY -->

<div class="reveal">
<h2>Our Previous Works</h2>
<div class="service-grid">
<div class="service-card"><img src="../uploads/images/annual/annualday1.png"></div>
<div class="service-card"><img src="../uploads/images/annual/annualday2.png"></div>
<div class="service-card"><img src="../uploads/images/annual/annualday3.png"></div>
<div class="service-card"><img src="../uploads/images/annual/annualday4.png"></div>
<div class="service-card"><img src="../uploads/images/annual/annualday5.png"></div>
<div class="service-card"><img src="../uploads/images/annual/annualday6.png"></div>
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

/* SCROLL REVEAL */
window.addEventListener("scroll",()=>{
document.querySelectorAll(".reveal").forEach(el=>{
if(el.getBoundingClientRect().top<window.innerHeight-100){
el.classList.add("active");
}
});
});

/* FEEDBACK SLIDER */
let current = 0;

const cards = document.querySelectorAll(".feedback-card");
const track = document.querySelector(".feedback-track");
const dotsContainer = document.getElementById("dots");

/* CREATE DOTS */
cards.forEach((_, i) => {
    let dot = document.createElement("span");
    dot.classList.add("dot");

    if (i === 0) dot.classList.add("active");

    dot.addEventListener("click", () => {
        current = i;
        updateCarousel();
    });

    dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".dot");

/* UPDATE FUNCTION */
function updateCarousel() {

    cards.forEach(card => card.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    cards[current].classList.add("active");
    dots[current].classList.add("active");

    /* 🔥 PERFECT CENTER CALCULATION */
    const cardWidth = cards[0].offsetWidth + 25; // width + gap
    const wrapperWidth = document.querySelector(".feedback-wrapper").offsetWidth;

    const offset = current * cardWidth;
    const center = (wrapperWidth / 2) - (cardWidth / 2);

    track.style.transform = `translateX(${center - offset}px)`;
}

/* INIT */
updateCarousel();

/* AUTO SLIDE */
let interval = setInterval(() => {
    current = (current + 1) % cards.length;
    updateCarousel();
}, 2500);

/* 🔥 PAUSE ON HOVER (PREMIUM FEEL) */
track.addEventListener("mouseenter", () => {
    clearInterval(interval);
});

track.addEventListener("mouseleave", () => {
    interval = setInterval(() => {
        current = (current + 1) % cards.length;
        updateCarousel();
    }, 2500);
});

/* 🔥 RESPONSIVE FIX */
window.addEventListener("resize", updateCarousel);

</script>

</body>
<?php include("../footer.php"); ?>
</html>
