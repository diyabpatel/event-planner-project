<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>College Event Planner</title>

<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
font-family:'Segoe UI',sans-serif;
background:radial-gradient(circle at top,#1e2a5a,#0b1020 70%);
color:#eaf0ff;
overflow-x:hidden;
}

/* HERO */
.hero{
position:relative;
height:100vh;
display:flex;
align-items:center;
padding:0 8%;
overflow:hidden;
}

.slides{
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
z-index:0;
}

.slide{
position:absolute;
width:100%;
height:100%;
background-size:cover;
background-position:center;
opacity:0;
animation:slideShow 30s infinite;
}

.slide:nth-child(1){background-image:url('uploads/images/annual/image2.jpg'); animation-delay:0s;}
.slide:nth-child(2){background-image:url('uploads/images/convocation/convocation1.jpg'); animation-delay:5s;}
.slide:nth-child(3){background-image:url('uploads/images/farewell/farewell2.jpg'); animation-delay:10s;}
.slide:nth-child(4){background-image:url('uploads/images/freshers/fresher1.jpg'); animation-delay:15s;}
.slide:nth-child(5){background-image:url('uploads/images/seminar/s2.jpeg'); animation-delay:20s;}
.slide:nth-child(6){background-image:url('uploads/images/sports day/sports day2.jpg'); animation-delay:25s;}

@keyframes slideShow{
0%{opacity:0}
5%{opacity:1}
20%{opacity:1}
25%{opacity:0}
100%{opacity:0}
}

.hero::after{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.55);
z-index:1;
}

.hero-box{
max-width:650px;
position:relative;
z-index:2;
}

.hero h1{
font-size:52px;
margin-bottom:20px;
}

.hero p{
font-size:18px;
margin-bottom:32px;
}

/* SECTION */
.section{
padding:90px 8%;
}

.section h2{
font-size:34px;
margin-bottom:20px;
background:linear-gradient(90deg,#9bb6ff,#e0e7ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:30px;
margin-top:40px;
}

/* CARD */
.card{
background:rgba(255,255,255,0.12);
backdrop-filter:blur(15px);
border-radius:16px;
padding:20px;
transition:.3s;
}
.card:hover{
transform:translateY(-6px);
}

/* FEATURES */
.features{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-top:30px;
}

.feature{
background:rgba(0,0,0,.4);
padding:20px;
border-radius:14px;
text-align:center;
}

/* GALLERY */
.gallery img{
width:100%;
height:200px;
object-fit:cover;
border-radius:12px;
}

/* STATS */
.stats h3{
font-size:28px;
color:#7aa2ff;
}

/* CTA */
.cta{
padding:100px 8%;
text-align:center;
background:linear-gradient(135deg,#233a7a,#0b1020);
}
.cta button{
padding:15px 35px;
border:none;
border-radius:40px;
background:#7aa2ff;
color:white;
cursor:pointer;
}

/* FOOTER */
.footer{
padding:30px;
text-align:center;
background:#050814;
}

/* SCROLL ANIMATION */
.reveal{
opacity:0;
transform:translateY(40px);
transition:all .8s ease;
}
.reveal.show{
opacity:1;
transform:none;
}

</style>
</head>

<body>
<?php include("navbar.php"); ?>

<!-- HERO -->
<div class="hero">
<div class="slides">
<div class="slide"></div>
<div class="slide"></div>
<div class="slide"></div>
<div class="slide"></div>
<div class="slide"></div>
<div class="slide"></div>
</div>

<div class="hero-box reveal show">
<h1>Powering Unforgettable College Events</h1>
<p>Plan, manage and execute college events professionally.</p>
</div>
</div>

<!-- HOW IT WORKS -->
<div class="section reveal">
<h2>How It Works</h2>
<div class="features">
<div class="feature">1️⃣ Choose Event</div>
<div class="feature">2️⃣ Select Package</div>
<div class="feature">3️⃣ Book Date</div>
<div class="feature">4️⃣ Pay Advance</div>
</div>
</div>

<!-- EVENT CATEGORIES -->
<div class="section reveal">
<h2>Event Categories</h2>
<div class="grid">
<div class="card">🎭 Cultural Fest</div>
<div class="card">🎤 Seminars</div>
<div class="card">🎓 Farewell</div>
<div class="card">🏆 Sports</div>
</div>
</div>

<!-- GALLERY -->
<div class="section reveal">
<h2>Event Highlights</h2>
<div class="grid gallery">
<img src="uploads/images/annual/image2.jpg">
<img src="uploads/images/farewell/farewell2.jpg">
<img src="uploads/images/freshers/fresher1.jpg">
<img src="uploads/images/sports day/sports day2.jpg">
</div>
</div>

<!-- TESTIMONIALS -->
<div class="section reveal">
<h2>What Students Say</h2>
<div class="features">
<div class="feature">⭐ Amazing experience! - BCA Student</div>
<div class="feature">⭐ Smooth booking! - MBA Student</div>
<div class="feature">⭐ Best farewell ever! - Engineering</div>
</div>
</div>

<!-- STATS -->
<div class="section reveal stats">
<h2>Our Impact</h2>
<div class="features">
<div class="feature"><h3>50+</h3>Events</div>
<div class="feature"><h3>1000+</h3>Students</div>
<div class="feature"><h3>20+</h3>Colleges</div>
<div class="feature"><h3>4.8★</h3>Rating</div>
</div>
</div>

<!-- WHY US -->
<div class="section reveal">
<h2>Why Choose Us</h2>
<div class="features">
<div class="feature">✔ Budget Friendly</div>
<div class="feature">✔ Easy Booking</div>
<div class="feature">✔ Secure Payments</div>
<div class="feature">✔ Faculty Approved</div>
</div>
</div>

<!-- CTA -->
<div class="cta reveal">
<h2>Ready to Organize Your Event?</h2>
<button onclick="location.href='login.php'">Get Started</button>
</div>

<!-- FOOTER -->
<div class="footer">
© <?php echo date('Y'); ?> College Event Planner
</div>

<script>
const els=document.querySelectorAll('.reveal');
const reveal=()=>{
els.forEach(el=>{
const r=el.getBoundingClientRect();
if(r.top<window.innerHeight-80){
el.classList.add('show');
}
});
};
window.addEventListener('scroll',reveal);
reveal();
</script>

</body>
</html>