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
height:100vh;
display:flex;
align-items:center;
padding:0 8%;
background:
linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)),
url('https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1600&q=80');
background-size:cover;
background-position:center;
}
.hero-box{
max-width:650px;
}
.hero h1{
font-size:52px;
line-height:1.15;
margin-bottom:20px;
}
.hero p{
font-size:18px;
opacity:.9;
margin-bottom:32px;
}
.hero button{
padding:15px 36px;
border:none;
border-radius:40px;
font-size:16px;
cursor:pointer;
margin-right:16px;
background:linear-gradient(135deg,#7aa2ff,#4f7cff);
color:white;
box-shadow:0 18px 40px rgba(122,162,255,.6);
}

/* SECTION */
.section{
padding:95px 8%;
}
.section h2{
font-size:34px;
margin-bottom:18px;
background:linear-gradient(90deg,#9bb6ff,#e0e7ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}
.section p{
max-width:720px;
line-height:1.7;
opacity:.92;
}

/* EVENT CARDS */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:30px;
margin-top:40px;
}
.card{
background:rgba(255,255,255,0.14);
backdrop-filter:blur(18px);
border-radius:18px;
padding:26px;
box-shadow:0 20px 50px rgba(0,0,0,0.65);
transition:.35s;
}
.card:hover{
transform:translateY(-8px);
box-shadow:0 30px 70px rgba(0,0,0,0.85);
}
.card img{
width:100%;
border-radius:14px;
margin-bottom:18px;
}
.card h3{
margin-bottom:10px;
color:#9bb6ff;
}

/* WHY US */
.features{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
gap:26px;
margin-top:35px;
}
.feature{
background:rgba(0,0,0,.45);
padding:24px;
border-radius:18px;
text-align:center;
}

/* CTA */
.cta{
padding:100px 8%;
text-align:center;
background:linear-gradient(135deg,#233a7a,#0b1020);
}
.cta h2{
font-size:36px;
margin-bottom:18px;
}
.cta button{
padding:16px 42px;
border:none;
border-radius:40px;
font-size:17px;
cursor:pointer;
background:#7aa2ff;
color:white;
box-shadow:0 18px 40px rgba(122,162,255,.6);
}

/* FOOTER */
.footer{
padding:35px 8%;
background:#050814;
text-align:center;
font-size:14px;
opacity:.85;
}

/* SCROLL ANIMATION */
.reveal{
opacity:0;
transform:translateY(50px);
transition:all .8s ease;
}
.reveal.show{
opacity:1;
transform:none;
}

/* RESPONSIVE */
@media(max-width:768px){
.hero h1{font-size:38px}
}
</style>
</head>

<body>
<?php include("navbar.php"); ?>
<!-- HERO -->
<div class="hero">
<div class="hero-box reveal show">
<h1>Powering Unforgettable College Events</h1>
<p>
From cultural fests to seminars and farewell nights —  
we plan, manage and execute college events professionally.
</p>

</div>
</div>

<!-- EVENTS -->
<div class="section reveal" id="events">
<h2>College Event Categories</h2>

<div class="grid">
<div class="card">
<img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=80">
<h3>🎭 Cultural Fest</h3>
<p>Dance, music, drama, fashion shows and competitions.</p>
</div>

<div class="card">
<img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=600&q=80">
<h3>🎤 Seminars & Workshops</h3>
<p>Expert talks, workshops and knowledge-sharing sessions.</p>
</div>

<div class="card">
<img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80">
<h3>🎓 Farewell & Freshers</h3>
<p>Memorable farewell nights and energetic freshers parties.</p>
</div>

<div class="card">
<img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=600&q=80">
<h3>🏆 Technical & Sports Events</h3>
<p>Hackathons, coding events, sports meets and competitions.</p>
</div>
</div>
</div>

<!-- WHY US -->
<div class="section reveal" id="why">
<h2>Why Choose Us</h2>

<div class="features">
<div class="feature">✔ Student-Friendly Planning</div>
<div class="feature">✔ Budget-Friendly Packages</div>
<div class="feature">✔ Faculty Approved Workflow</div>
<div class="feature">✔ Secure Online Booking</div>
</div>
</div>

<!-- CTA -->
<div class="cta reveal" id="contact">
<h2>Ready to Organize Your College Event?</h2>
<p>Let us handle the planning while you enjoy the celebration.</p><br>
<button onclick="location.href='login.php'">Get Started</button>
</div>

<!-- FOOTER -->
<div class="footer">
© <?php echo date('Y'); ?> College Event Planner | Made for College Projects
</div>

<script>
/* SCROLL REVEAL */
const els=document.querySelectorAll('.reveal');
const reveal=()=>els.forEach(el=>{
const r=el.getBoundingClientRect();
if(r.top<window.innerHeight-80) el.classList.add('show');
});
window.addEventListener('scroll',reveal);
reveal();
</script>

</body>
</html>