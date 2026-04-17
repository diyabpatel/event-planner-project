<?php
session_start();
include("db.php");

$events = mysqli_query($conn,"SELECT * FROM events");

$feedbacks = mysqli_query($conn,"
SELECT f.*, e.event_name 
FROM feedback f 
JOIN events e ON f.event_id = e.event_id
GROUP BY f.event_id
");
$avgQuery = "
SELECT AVG(f.rating) AS avg_rating
FROM feedback f
JOIN bookings b ON f.booking_id = b.booking_id
JOIN events e ON b.event_id = e.event_id
WHERE e.event_name = 'Convocation'
";

$avgResult = mysqli_query($conn, $avgQuery);
$avgData = mysqli_fetch_assoc($avgResult);

$avgRating = round($avgData['avg_rating'], 1); // 1 decimal (e.g. 4.7)
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
margin:0;
padding:0;
background:#ffffff; /* match your section */
overflow-x:hidden;
}

/* ===== HERO (UNCHANGED) ===== */
/* HERO SECTION */
/* HERO LAYOUT */
/* HERO */
.hero{
    display:flex;
    align-items:flex-start;   /* 🔥 TOP ALIGN */
    justify-content:space-between;
    padding:50px 8% 0;       /* 🔥 less top space */
    height:100vh;
    background:linear-gradient(135deg,#fdfbff,#f3f0ff);
}

/* LEFT */
.hero-left{
    width:45%;
    padding: 100px 0 0 0px;
}

.hero-left h1{
    font-size:42px;
    font-weight:700;
    color:#4c1d95;
}

.hero-left p{
    margin-top:10px;
    color:#6d28d9;
}

/* RIGHT */
.hero-right{
    width:50%;
    perspective:1200px;
    overflow:hidden;
}

/* SLIDER */
.slides{
    position:relative;
    height:460px;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* SLIDES */
.slide{
    position:absolute;
    width:360px;          /* equal width */
    aspect-ratio:1/1;     /* 🔥 PERFECT SQUARE */
    border-radius:20px;
    background-size:cover;
    background-position:center;
    transition:0.6s ease;
    box-shadow:0 15px 40px rgba(124,58,237,0.25);
}

/* CENTER */
.slide.active{
    transform:translateX(0) scale(1.2);
    z-index:5;
}

.slide img{
    width:100%;
    height:100%;
    object-fit:cover; /* 🔥 no stretch */
    border-radius:20px;
}

/* LEFT */
.slide.left{
    transform:translateX(-260px) rotateY(35deg) scale(0.9);
    opacity:0.6;
}

/* RIGHT */
.slide.right{
    transform:translateX(260px) rotateY(-35deg) scale(0.9);
    opacity:0.6;
}

/* FAR */
.slide.far-left{
    transform:translateX(-420px) scale(0.7);
    opacity:0.3;
}

.slide.far-right{
    transform:translateX(420px) scale(0.7);
    opacity:0.3;
}

/* OPTIONAL GLOW */
.slide::after{
    content:"";
    position:absolute;
    inset:0;
    border-radius:20px;
    background:linear-gradient(180deg,transparent,rgba(124,58,237,0.25));
}

/* ===== PREMIUM HOW IT WORKS (UPDATED) ===== */
/* 🔥 FULL SECTION FIXED */
.how-section{
    min-height:100vh;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    padding:80px 6%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);

    /* PREMIUM ENTRY */
    opacity:0;
    transform: translateY(60px);
    animation: fadeInUp 1s ease forwards;
}

@keyframes fadeInUp{
    to{
        opacity:1;
        transform: translateY(0);
    }
}

/* ===== LEFT SIDE ===== */
.how-left{
    width:40%;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

/* HEADING (GRADIENT PREMIUM) */
.how-left h2{
    font-size:34px;
    margin-bottom:15px;

    background: linear-gradient(90deg,#7c3aed,#c4b5fd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* TAGLINE */
.how-tagline{
    color:#7c3aed;
    font-weight:600;
    margin-bottom:15px;
    font-size:16px;
}

/* DESCRIPTION */
.how-desc{
    color:#6b7280;
    margin-bottom:20px;
    line-height:1.6;
}

/* POINTS */
.how-points div{
    margin-bottom:10px;
    color:#4c1d95;
    font-size:14px;
}

/* ===== RIGHT SIDE GRID ===== */
.timeline{
    width:55%;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
    align-content:center;
}

/* ===== STEP ===== */
.step{
    display:flex;
    align-items:flex-start;
    gap:14px;

    /* FLOAT ANIMATION */
    animation: floaty 6s ease-in-out infinite;
}

/* FLOAT DELAYS */
.step:nth-child(2){ animation-delay:0.5s; }
.step:nth-child(3){ animation-delay:1s; }
.step:nth-child(4){ animation-delay:1.5s; }
.step:nth-child(5){ animation-delay:2s; }
.step:nth-child(6){ animation-delay:2.5s; }
.step:nth-child(7){ animation-delay:3s; }
.step:nth-child(8){ animation-delay:3.5s; }

@keyframes floaty{
    0%{ transform: translateY(0px); }
    50%{ transform: translateY(-6px); }
    100%{ transform: translateY(0px); }
}

/* ===== NUMBER ===== */
.step-number{
    min-width:42px;
    height:42px;
    background:#7c3aed;
    color:#fff;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-size:15px;

    transition:0.4s;
}

/* GLOW ON HOVER */
.step:hover .step-number{
    transform: scale(1.1);
    box-shadow:
        0 0 15px rgba(124,58,237,0.8),
        0 0 30px rgba(167,139,250,0.6);
}

/* ===== STEP BOX (GLASS PREMIUM) ===== */
.step-box{
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    padding:18px;
    border-radius:14px;
    border:1px solid rgba(124,58,237,0.15);

    width:100%;
    min-height:85px;

    display:flex;
    flex-direction:column;
    justify-content:center;

    transition:0.4s;

    box-shadow: 0 10px 30px rgba(124,58,237,0.08);
}

/* HOVER PREMIUM */
.step-box:hover{
    transform: translateY(-10px) scale(1.02);
    box-shadow:
        0 20px 40px rgba(124,58,237,0.25),
        0 0 20px rgba(167,139,250,0.4);
}

/* ===== TEXT ===== */
.step-title{
    color:#4c1d95;
    font-size:16px;
    font-weight:600;
    margin-bottom:6px;
}

.step-desc{
    color:#6b7280;
    font-size:13px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
    .how-section{
        flex-direction:column;
        padding:50px 5%;
    }

    .how-left,
    .timeline{
        width:100%;
    }

    .timeline{
        grid-template-columns:1fr;
    }
}
/* ===== SCROLL ANIMATION ===== */
.fade-up{
    opacity: 0;
    transform: translateY(80px);
    transition: all 1s ease;
}

.fade-item{
    opacity: 0;
    transform: translateY(50px) scale(0.95);
    transition: all 0.7s cubic-bezier(0.22, 1, 0.36, 1);
}

/* visible */
.fade-up.show{
    opacity: 1;
    transform: translateY(0);
}

.fade-item.show{
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* 🔥 STAGGER FOR STEPS */
.step:nth-child(1){ transition-delay:0.1s; }
.step:nth-child(2){ transition-delay:0.2s; }
.step:nth-child(3){ transition-delay:0.3s; }
.step:nth-child(4){ transition-delay:0.4s; }
.step:nth-child(5){ transition-delay:0.5s; }
.step:nth-child(6){ transition-delay:0.6s; }
.step:nth-child(7){ transition-delay:0.7s; }
.step:nth-child(8){ transition-delay:0.8s; }
/* ===== EVENT CATEGORIES ===== */
/* ===== SECTION ===== */
.event-section{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:20px 5%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
    overflow:hidden; /* 🔥 important */
}

/* ===== GRID ===== */
.event-grid{
    width:35%;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px; /* reduced */
}

/* ===== CARD ===== */
.event-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,0.07);
    transition:0.3s;
    text-align:center;
    text-decoration:none;
    color:inherit;
    width: 250px;
    height: 250px;
    padding: 17px 27px 27px ;
}

/* 🔥 SQUARE IMAGE (SMALLER FOR FIT) */
.img-box{
    width:120px;
    height:120px;   /* 🔥 smaller image */
}

/* IMAGE */
.img-box img{
    width:200px;
    height:200px;
    object-fit:cover;
}

/* TITLE */
.event-card h3{
    position:static;   /* 🔥 important */
    margin-top:85px;
    text-align:center;
    width:100%;
    color:#4c1d95;
    font-size:14px;
}

/* HOVER */
.event-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 25px rgba(124,58,237,0.18);
}

/* ===== TEXT SIDE ===== */
.event-text{
    width:40%;
}

/* HEADING */
.event-text h2{
    font-size:28px; /* reduced */
    color:#3b0764;
    margin-bottom:12px;
}

/* TAGLINE */
.tagline{
    color:#7c3aed;
    font-weight:600;
    margin-bottom:10px;
    font-size:14px;
}

/* DESCRIPTION */
.desc{
    color:#6d28d9 !important;  /* 🔥 force override */
    margin-bottom:12px;
    line-height:1.5;
    font-size:13px;
}

/* FEATURES */
.features div{
    margin-bottom:6px;
    color:#4c1d95;
    font-size:13px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .event-section{
        flex-direction:column;
        height:auto;
        overflow:visible;
    }

    .event-grid,
    .event-text{
        width:100%;
    }
}
/* ===== SCROLL ANIMATION ===== */

/* section animation */
.fade-up{
    opacity: 0;
    transform: translateY(80px);
    transition: all 1s ease;
}

/* card animation */
.fade-item{
    opacity: 0;
    transform: translateY(50px) scale(0.95);
    transition: all 0.7s cubic-bezier(0.22, 1, 0.36, 1);
}

/* when visible */
.fade-up.show{
    opacity: 1;
    transform: translateY(0);
}

.fade-item.show{
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* 🔥 STAGGER EFFECT (premium) */
.event-card:nth-child(1){ transition-delay:0.1s; }
.event-card:nth-child(2){ transition-delay:0.2s; }
.event-card:nth-child(3){ transition-delay:0.3s; }
.event-card:nth-child(4){ transition-delay:0.4s; }
.event-card:nth-child(5){ transition-delay:0.5s; }
.event-card:nth-child(6){ transition-delay:0.6s; }

/* EXTRA PREMIUM HOVER */
.event-card{
    transition: all 0.4s ease;
}

.event-card:hover{
    transform: translateY(-10px) scale(1.04);
    box-shadow: 
        0 20px 40px rgba(124,58,237,0.25),
        0 0 20px rgba(167,139,250,0.3);
}


/* ===== BEST SELLER FULL SCREEN ===== */
/* ===== ULTRA PREMIUM BEST SELLER ===== */
.bestseller-section{
    position:relative;
    height:100vh;
    width:100%;
    overflow:hidden;
}

/* 🎥 VIDEO BACKGROUND */
.bg-video{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:1;

    filter: brightness(0.9) contrast(1.2) saturate(1.1);
}

/* 🌑 DARK + GRADIENT OVERLAY */
.overlay{
    background:linear-gradient(
        to right,
        rgba(0,0,0,0.4),
        rgba(0,0,0,0.2),
        rgba(0,0,0,0.4)
    );
}

/* CENTER CONTENT */
.bestseller-content{
    position:relative;
    z-index:3;
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* 💎 GLASS CARD */
.glass-card{
    width:420px;
    padding:30px;
    border-radius:20px;
    backdrop-filter:blur(8px);
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.2);
    box-shadow:0 20px 60px rgba(0,0,0,0.4);
    text-align:center;
    color:#fff;

    animation:fadeUp 1s ease;
}

/* IMAGE */
.glass-card img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:12px;
    margin-bottom:15px;
}

/* TEXT */
.glass-card h2{
    font-size:28px;
    margin-bottom:10px;
}

.rating{
    color:#c4b5fd;
    font-weight:600;
    margin-bottom:10px;
}

.desc{
    font-size:14px;
    color:#e5e7eb;
    margin-bottom:20px;
}

/* BUTTON */
.btn{
    padding:12px 25px;
    background:linear-gradient(135deg,#7c3aed,#a78bfa);
    border:none;
    color:#fff;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn:hover{
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(124,58,237,0.5);
}

/* ✨ ANIMATION */
@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(40px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* 📱 MOBILE */
@media(max-width:900px){
    .glass-card{
        width:90%;
        padding:20px;
    }

    .glass-card h2{
        font-size:22px;
    }
}
/* ===== FEEDBACK===== */
/* SECTION */
.feedback-section{
    text-align:center;
    padding:60px 5%;
    background:linear-gradient(135deg,#fdfbff,#f3f0ff);
}

/* HEADING */
.feedback-section h2{
    color:#4c1d95;
    margin-bottom:30px;
    font-size:28px;
}

/* SLIDER */
.feedback-slider{
    position:relative;
    width:100%;
    margin:auto;
    overflow:hidden;
}

/* CONTAINER */
.feedback-container{
    display:flex;
    transition:transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}

/* CARD */
.feedback-card{
    width:100%;
    min-width:100%;
    flex:0 0 100%;

    background:rgba(255,255,255,0.9);
    backdrop-filter:blur(10px);

    padding:30px;
    border-radius:20px;

    box-shadow:0 15px 40px rgba(124,58,237,0.15);

    transition:0.4s;
}

/* HOVER */
.feedback-card:hover{
    transform:scale(1.02);
    box-shadow:0 20px 50px rgba(124,58,237,0.25);
}

/* TEXT */
.feedback-text{
    margin:15px 0;
    color:#374151;
    font-size:15px;
    line-height:1.6;
}

/* EVENT */
.event-name{
    color:#7c3aed;
    font-weight:600;
}

/* NAV BUTTONS */
.nav{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:white;
    border:none;
    width:45px;
    height:45px;
    border-radius:50%;
    cursor:pointer;
    font-size:20px;
    z-index:10;

    display:flex;
    align-items:center;
    justify-content:center;

    box-shadow:0 10px 25px rgba(124,58,237,0.4);
    transition:0.3s;
}

.prev{ left:25px; }
.next{ right:15px; }

.nav:hover{
    transform:translateY(-50%) scale(1.1);
}
.stars{
    font-size:18px;
    margin-bottom:10px;
    color:#facc15; /* 🔥 golden stars */
    letter-spacing:2px;
}

/* rating text */
.rating-text{
    color:#6b7280;
    font-size:14px;
    margin-left:8px;
}

</style>
</head>

<body>

<?php include("navbar.php"); ?>

<!-- HERO -->
<div class="hero">

    <!-- LEFT CONTENT -->
    <div class="hero-left">

    <h1>
        Powering <span class="highlight">Unforgettable</span><br>
        College Events
    </h1>

    <p class="sub-text">
        Plan, manage and execute college events professionally — 
        from concept to celebration.
    </p>

    <p class="tagline">
        ✨ From Freshers to Farewells — we make every moment extraordinary.
    </p>

   

</div>

    <!-- RIGHT SLIDER -->
    <div class="hero-right">
        <div class="slides">
    <div class="slide" style="background-image:url('uploads/images/events_images/convocation.png')"></div>
    <div class="slide" style="background-image:url('uploads/images/events_images/annualday.png')"></div>
    <div class="slide" style="background-image:url('uploads/images/farewell/farewell5.png')"></div>
    <div class="slide" style="background-image:url('uploads/images/seminar/speaker_setup.png')"></div>
    <div class="slide" style="background-image:url('uploads/images/sports day/sport_day_about.png')"></div>
</div>
    </div>

</div>

<script>
let slides = document.querySelectorAll(".slide");
let index = 0;

function updateSlider(){

    slides.forEach(s=>{
        s.className = "slide";
    });

    let total = slides.length;

    slides[index].classList.add("active");
    slides[(index-1+total)%total].classList.add("left");
    slides[(index+1)%total].classList.add("right");
    slides[(index-2+total)%total].classList.add("far-left");
    slides[(index+2)%total].classList.add("far-right");
}

function autoSlide(){
    index = (index+1)%slides.length;
    updateSlider();
}

updateSlider();
setInterval(autoSlide,3000);
</script>

<!-- EVENT CATEGORIES -->
<div class="event-section fade-up">

    <!-- LEFT SIDE -->
    <div class="event-grid">

        <a href="events/annualday.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/annualday.png">
            </div>
            <h3>Annual Day</h3>
        </a>

        <a href="events/convocation.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/convocation.png">
            </div>
            <h3>Convocation</h3>
        </a>

        <a href="events/farewell.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/farewell.PNG">
            </div>
            <h3>Farewell</h3>
        </a>

        <a href="events/fresher.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/fresher.PNG">
            </div>
            <h3>Freshers</h3>
        </a>

        <a href="events/seminar.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/seminar.PNG">
            </div>
            <h3>Seminar</h3>
        </a>

        <a href="events/sportsday.php" class="event-card fade-item">
            <div class="img-box">
                <img src="uploads/images/events_images/sportsday.PNG">
            </div>
            <h3>Sports Day</h3>
        </a>

    </div>

    <!-- RIGHT SIDE -->
    <div class="event-text fade-item">

        <h2>Crafting Memorable College Experiences</h2>

        <p class="tagline">
            “From planning to perfection — we bring your events to life.”
        </p>

        <p class="desc">
            Whether it’s a grand Annual Day, an emotional Farewell, or a professional Seminar,
            our platform helps you organize events seamlessly.
        </p>

        <div class="features">
            <div>✔ Smart Planning</div>
            <div>✔ Creative Execution</div>
            <div>✔ Budget Friendly Packages</div>
            <div>✔ Trusted by Colleges</div>
        </div>

    </div>

</div>

<!-- HOW IT WORKS (UPDATED ONLY THIS PART) -->
<div class="how-section fade-up">

    <!-- LEFT SIDE -->
    <div class="how-left fade-item">

        <h2>How EventHub Works</h2>

        <p class="how-tagline">
            “Plan smarter. Execute better. Celebrate bigger.”
        </p>

        <p class="how-desc">
            EventHub simplifies the entire event planning process for colleges.
        </p>

        <div class="how-points">
            <div>✔ Simple step-by-step process</div>
            <div>✔ Fully customizable options</div>
            <div>✔ End-to-end event management</div>
            <div>✔ Designed for students & organizers</div>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="timeline">

        <div class="step fade-item">
            <div class="step-number">1</div>
            <div class="step-box">
                <div class="step-title">Choose Event</div>
                <div class="step-desc">Select your preferred event type.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">2</div>
            <div class="step-box">
                <div class="step-title">Select Package</div>
                <div class="step-desc">Pick a package that suits you.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">3</div>
            <div class="step-box">
                <div class="step-title">Capacity</div>
                <div class="step-desc">Define number of guests.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">4</div>
            <div class="step-box">
                <div class="step-title">Venue</div>
                <div class="step-desc">Choose your event location.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">5</div>
            <div class="step-box">
                <div class="step-title">Decoration</div>
                <div class="step-desc">Customize themes & decor.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">6</div>
            <div class="step-box">
                <div class="step-title">Seating</div>
                <div class="step-desc">Arrange seating style.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">7</div>
            <div class="step-box">
                <div class="step-title">Food</div>
                <div class="step-desc">Select menu options.</div>
            </div>
        </div>

        <div class="step fade-item">
            <div class="step-number">8</div>
            <div class="step-box">
                <div class="step-title">Date</div>
                <div class="step-desc">Finalize your schedule.</div>
            </div>
        </div>

    </div>


</div>

<!-- BEST SELLER SECTION -->
<!-- ULTRA PREMIUM BEST SELLER -->
<div class="bestseller-section">

    <!-- BACKGROUND VIDEO -->
    <video autoplay muted loop playsinline class="bg-video">
        <source src="uploads/videos/convocation.mp4" type="video/mp4">
    </video>

    <!-- DARK OVERLAY -->
    <div class="overlay"></div>

    <!-- CONTENT -->
    <div class="bestseller-content">

        <div class="glass-card">

            <img src="uploads/images/events_images/convocation.png" alt="Convocation">

            <h2>Convocation</h2>

            <p class="rating">
    ⭐ <?= $avgRating ? $avgRating : '0.0' ?> / 5 — Top Rated Experience
</p>

            <p class="desc">
                Celebrate success with a premium convocation experience —
                elegant stage design, cinematic lighting, and flawless execution.
            </p>

            <a href="events/convocation.php" class="btn">Explore Event</a>

        </div>

    </div>

</div>

<!-- CLIENT FEEDBACK -->
<?php
$query = "
SELECT f.rating, f.comment, e.event_name
FROM feedback f
JOIN bookings b ON f.booking_id = b.booking_id
JOIN events e ON b.event_id = e.event_id
WHERE f.feedback_id IN (
    SELECT MAX(f2.feedback_id)
    FROM feedback f2
    JOIN bookings b2 ON f2.booking_id = b2.booking_id
    GROUP BY b2.event_id
)
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}
?>
<div class="feedback-section fade-up">
    <h2>What Our Clients Say</h2>
    <button class="nav prev" onclick="prevSlide()">❮</button>
    <div class="feedback-slider">

        

        <div class="feedback-container">

            <?php while($f=mysqli_fetch_assoc($result)){ ?>
            <div class="feedback-card fade-item">

                <div class="stars">
<?php 
$rating = (int)$f['rating'];

for($i=1; $i<=5; $i++){
    if($i <= $rating){
        echo "⭐"; // filled
    } else {
        echo "☆"; // empty
    }
}
?>
<span class="rating-text">(<?= $rating ?>/5)</span>
</div>

                <p class="feedback-text">
                    <?= $f['comment'] ?>
                </p>

                <h4 class="event-name">
                    <?= $f['event_name'] ?>
                </h4>

            </div>
            <?php } ?>

        </div>

        

    </div>
    <button class="nav next" onclick="nextSlide()">❯</button>
</div>

<script>
let fIndex = 0;
let autoSlideInterval;

function showSlide(){
    let slider = document.querySelector(".feedback-container");
    let cards = document.querySelectorAll(".feedback-card");
    let total = cards.length;

    if(total === 0) return;

    if(fIndex >= total) fIndex = 0;
    if(fIndex < 0) fIndex = total - 1;

    slider.style.transform = "translateX(" + (-fIndex * 100) + "%)";
}

/* NEXT */
function nextSlide(){
    fIndex++;
    showSlide();
    resetAutoSlide();
}

/* PREV */
function prevSlide(){
    fIndex--;
    showSlide();
    resetAutoSlide();
}

/* AUTO SLIDE */
function startAutoSlide(){
    autoSlideInterval = setInterval(() => {
        fIndex++;
        showSlide();
    }, 3000); // 3 sec
}

/* RESET TIMER AFTER CLICK */
function resetAutoSlide(){
    clearInterval(autoSlideInterval);
    startAutoSlide();
}

/* INIT */
showSlide();
startAutoSlide();
</script>
<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {

        if(entry.isIntersecting){

            entry.target.classList.add("show");

            const items = entry.target.querySelectorAll(".fade-item");

            items.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add("show");
                }, index * 120);
            });

        }

    });
}, {
    threshold: 0.2
});

document.querySelectorAll(".fade-up").forEach(el => {
    observer.observe(el);
});
</script>
</body>
<?php include("footer.php"); ?>
</html>