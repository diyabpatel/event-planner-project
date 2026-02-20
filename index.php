<?php
session_start();
include("db.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>College Event Management System</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',Arial,sans-serif;
    background:#0b0f1a;
    color:#fff;
    overflow-x:hidden;
}

/* ===== HERO WITH BG SLIDESHOW ===== */
.hero{
    height:calc(100vh - 72px);
    position:relative;
}
.hero-bg{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    animation:bgSlide 15s infinite;
}
.hero-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.65);
}
.hero-content{
    position:relative;
    z-index:2;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:40px;
}
.hero-content h1{
    font-size:44px;
    background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}
.hero-content p{
    font-size:20px;
    margin-top:18px;
    opacity:0.9;
}

/* background animation */
@keyframes bgSlide{
    0%{background-image:url('uploads/images/bg1.jpg')}
    33%{background-image:url('uploads/images/bg2.jpg')}
    66%{background-image:url('uploads/images/bg3.jpg')}
    100%{background-image:url('uploads/images/bg1.jpg')}
}

/* ===== SECTION ===== */
.section{
    padding:90px 8%;
    text-align:center;
}
.section h2{
    font-size:32px;
    margin-bottom:50px;
    background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* ===== EVENTS ===== */
.events{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:35px;
}
.event-card{
    background:rgba(255,255,255,0.06);
    border-radius:20px;
    overflow:hidden;
    backdrop-filter:blur(14px);
    transform:translateY(40px);
    opacity:0;
    transition:0.7s ease;
}
.event-card.show{
    transform:translateY(0);
    opacity:1;
}
.event-card img{
    width:100%;
    height:200px;
    object-fit:cover;
}
.event-card-content{
    padding:22px;
}
.event-card a{
    display:inline-block;
    margin-top:15px;
    padding:10px 22px;
    background:linear-gradient(135deg,#7aa2ff,#4f7cff);
    color:white;
    text-decoration:none;
    border-radius:30px;
    font-size:14px;
}

/* ===== FEEDBACK CAROUSEL ===== */
.feedback-wrapper{
    overflow:hidden;
    position:relative;
}
.feedback-track{
    display:flex;
    gap:25px;
    transition:0.6s ease;
}
.feedback-card{
    min-width:300px;
    background:rgba(255,255,255,0.08);
    padding:25px;
    border-radius:18px;
    backdrop-filter:blur(16px);
    text-align:left;
}
.feedback-card b{
    display:block;
    margin-top:15px;
    color:#9bb6ff;
    font-weight:500;
}

/* ===== ABOUT & CONTACT ===== */
.about,.contact{
    max-width:900px;
    margin:auto;
    font-size:17px;
    line-height:1.7;
}

/* ===== FOOTER ===== */
footer{
    background:#070a14;
    padding:50px 8%;
    text-align:center;
}
.socials a{
    margin:10px;
    font-size:20px;
    text-decoration:none;
    color:#9bb6ff;
}
.socials a:hover{color:#fff}
</style>
</head>

<body>

<?php include("navbar.php"); ?>

<!-- HERO -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div>
            <h1>College Event Management System</h1>
            <p>Plan • Organize • Celebrate — all in one place</p>
        </div>
    </div>
</div>

<!-- EVENTS -->
<div class="section">
<h2>Our Events</h2>
<div class="events">
<?php
$ev=mysqli_query($conn,"SELECT * FROM events ORDER BY event_name");
while($e=mysqli_fetch_assoc($ev)){
$img=!empty($e['image'])?$e['image']:'uploads/images/default.jpg';
echo "
<div class='event-card animate'>
<img src='$img'>
<div class='event-card-content'>
<h3>{$e['event_name']}</h3>
<p>Complete planning with premium packages.</p>
<a href='{$e['page']}'>Book Now</a>
</div>
</div>";
}
?>
</div>
</div>

<!-- FEEDBACK -->
<div class="section">
<h2>Student Feedback</h2>

<div class="feedback-wrapper">
    <div class="feedback-track" id="feedbackTrack">
        <div class="feedback-card">
            Amazing arrangements and smooth coordination.
            <b>— Anjali Patel</b>
        </div>
        <div class="feedback-card">
            Our annual function was perfectly managed.
            <b>— Rahul Mehta</b>
        </div>
        <div class="feedback-card">
            Best event experience we ever had in college.
            <b>— Neha Shah</b>
        </div>
        <div class="feedback-card">
            Professional team and great support.
            <b>— Karan Desai</b>
        </div>
    </div>
</div>
</div>

<!-- ABOUT -->
<div class="section">
<h2>About Us</h2>
<div class="about">
We provide complete college event solutions including venue,
food, decoration, seating, photography and videography.
</div>
</div>

<!-- CONTACT -->
<div class="section">
<h2>Contact Us</h2>
<div class="contact">
📧 support@collegeevents.com<br>
📞 +91 98765 43210<br>
📍 Navsari, Gujarat
</div>
</div>

<!-- FOOTER -->
<footer>
<p>© 2026 College Event Management System</p>
<div class="socials">
<a href="#">📘</a>
<a href="#">📸</a>
<a href="#">🐦</a>
<a href="#">🌐</a>
</div>
</footer>

<script>
/* EVENT SCROLL ANIMATION */
const cards=document.querySelectorAll(".event-card");
window.addEventListener("scroll",()=>{
cards.forEach(c=>{
if(c.getBoundingClientRect().top < window.innerHeight-100){
c.classList.add("show");
}
});
});

/* FEEDBACK AUTO SWIPE */
let index=0;
const track=document.getElementById("feedbackTrack");
setInterval(()=>{
index++;
if(index>1) index=0;
track.style.transform=`translateX(-${index*330}px)`;
},3000);
</script>

</body>
</html>