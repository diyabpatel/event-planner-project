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

/* ===== HERO (UNCHANGED) ===== */
/* HERO SECTION */
/* HERO */
/* HERO MAIN */
.hero{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 8%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
}

/* LEFT SIDE */
.hero-left{
    width:50%;
}

.hero-left h1{
    font-size:52px;
    color:#3b0764;
    margin-bottom:20px;
    font-weight:700;
}

.hero-left p{
    font-size:18px;
    color:#6d28d9;
}

/* RIGHT SIDE */
.hero-right{
    width:50%;
    height:80%;
    position:relative;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

/* SLIDES */
.slides{
    position:absolute;
    width:100%;
    height:100%;
}

/* EACH SLIDE */
.slide{
    position:absolute;
    width:100%;
    height:100%;
    background-size:cover;
    background-position:center;
    opacity:0;
    animation:slideShow 30s infinite;

    /* 🔥 CLEAN IMAGE LOOK */
    filter:brightness(1.1) contrast(1.05);
}

/* IMAGES */
.slide:nth-child(1){background-image:url('uploads/images/annual/image2.jpg'); animation-delay:0s;}
.slide:nth-child(2){background-image:url('uploads/images/convocation/convocation1.jpg'); animation-delay:5s;}
.slide:nth-child(3){background-image:url('uploads/images/farewell/farewell2.jpg'); animation-delay:10s;}
.slide:nth-child(4){background-image:url('uploads/images/freshers/fresher1.jpg'); animation-delay:15s;}
.slide:nth-child(5){background-image:url('uploads/images/seminar/s2.jpeg'); animation-delay:20s;}
.slide:nth-child(6){background-image:url('uploads/images/sports day/sports day2.jpg'); animation-delay:25s;}

/* ANIMATION */
@keyframes slideShow{
0%{opacity:0}
5%{opacity:1}
20%{opacity:1}
25%{opacity:0}
100%{opacity:0}
}

/* RESPONSIVE */
@media(max-width:900px){
    .hero{
        flex-direction:column;
        justify-content:center;
        text-align:center;
    }

    .hero-left,
    .hero-right{
        width:100%;
    }

    .hero-right{
        margin-top:30px;
        height:300px;
    }

    .hero-left h1{
        font-size:36px;
    }
}

/* ===== PREMIUM HOW IT WORKS (UPDATED) ===== */
/* SECTION BASE */
.how-section{
    height:100vh;
    width:100%;
    padding:40px 6%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff,#ede9fe);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

/* HEADING (FIXED - DARKER) */
.how-section h2{
    color:#3b0764; /* DARK PURPLE */
    font-size:34px;
    margin-bottom:25px;
    letter-spacing:1px;
    position:relative;
    font-weight:700;
}

.how-section h2::after{
    content:'';
    width:70px;
    height:4px;
    background:#7c3aed;
    display:block;
    margin:10px auto 0;
    border-radius:5px;
}

/* TIMELINE GRID (TIGHT FIT) */
.timeline{
    width:100%;
    max-width:1100px;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
}

/* STEP */
.step{
    display:flex;
    align-items:flex-start;
    gap:10px;
}

/* NUMBER */
.step-number{
    min-width:36px;
    height:36px;
    background:linear-gradient(135deg,#9333ea,#7c3aed);
    color:#fff;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-size:14px;
    box-shadow:0 4px 10px rgba(124,58,237,0.4);
}

/* BOX */
.step-box{
    background:#ffffff;
    padding:14px;
    border-radius:10px;
    transition:0.25s;
    border:1px solid #e9d5ff;
    width:100%;
    box-shadow:0 5px 15px rgba(0,0,0,0.04);
}

/* HOVER */
.step-box:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 20px rgba(124,58,237,0.2);
    border-color:#c084fc;
}

/* TITLE */
.step-title{
    color:#4c1d95;
    font-size:15px;
    font-weight:600;
    margin-bottom:4px;
}

/* DESCRIPTION */
.step-desc{
    color:#6b7280;
    font-size:13px;
    line-height:1.4;
}

/* TABLET */
@media(max-width:1000px){
    .timeline{
        grid-template-columns:repeat(2,1fr);
    }
}

/* MOBILE */
@media(max-width:600px){
    .how-section{
        height:auto;
        padding:50px 5%;
    }

    .timeline{
        grid-template-columns:1fr;
        gap:15px;
    }
}

/* ===== EVENT CATEGORIES ===== */
/* SECTION */
.event-section{
    padding:70px 8%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
    text-align:center;
}

.event-section h2{
    font-size:34px;
    color:#3b0764;
    margin-bottom:40px;
    font-weight:700;
}

/* GRID */
.event-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:30px;
}

/* CARD */
.event-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
    cursor:pointer;
}

/* IMAGE */
.event-card img{
    width:100%;
    height:200px;
    object-fit:cover;
    transition:0.4s;
}

/* BODY */
.event-body{
    padding:20px;
}

/* TITLE */
.event-body h3{
    color:#4c1d95;
    margin-bottom:15px;
}

/* BUTTON */
.event-body button{
    padding:10px 20px;
    border:none;
    background:linear-gradient(135deg,#9333ea,#7c3aed);
    color:#fff;
    border-radius:25px;
    cursor:pointer;
    transition:0.3s;
}

/* HOVER EFFECTS */
.event-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(124,58,237,0.2);
}

.event-card:hover img{
    transform:scale(1.08);
}

.event-body button:hover{
    background:linear-gradient(135deg,#7c3aed,#6d28d9);
}

/* RESPONSIVE */
@media(max-width:1000px){
    .event-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:600px){
    .event-grid{
        grid-template-columns:1fr;
    }
}

/* ===== FEEDBACK===== */
/* SECTION */
.feedback-section{
    height:100vh;
    padding:50px 8%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff,#ede9fe);
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}

/* HEADING */
.feedback-section h2{
    font-size:34px;
    color:#3b0764;
    margin-bottom:40px;
    font-weight:700;
}

/* GRID */
.feedback-grid{
    width:100%;
    max-width:1100px;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:25px;
}

/* CARD */
.feedback-card{
    background:#ffffff;
    padding:22px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
    border:1px solid #ede9fe;
    transition:0.3s;
}

/* HOVER */
.feedback-card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(124,58,237,0.15);
}

/* STARS */
.stars{
    font-size:16px;
    font-weight:600;
    color:#7c3aed;
    margin-bottom:12px;
}

/* TEXT */
.feedback-text{
    font-size:14px;
    color:#4b5563;
    line-height:1.6;
    margin-bottom:15px;
}

/* EVENT NAME */
.event-name{
    color:#5b21b6;
    font-weight:600;
}

/* RESPONSIVE */
@media(max-width:1000px){
    .feedback-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:600px){
    .feedback-section{
        height:auto;
        padding:60px 5%;
    }

    .feedback-grid{
        grid-template-columns:1fr;
    }
}



</style>
</head>

<body>

<?php include("navbar.php"); ?>

<!-- HERO -->
<div class="hero">

    <!-- LEFT CONTENT -->
    <div class="hero-left">
        <h1>Powering Unforgettable College Events</h1>
        <p>Plan, manage and execute college events professionally.</p>
    </div>

    <!-- RIGHT SLIDER -->
    <div class="hero-right">
        <div class="slides">
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>
    </div>

</div>

<!-- HOW IT WORKS (UPDATED ONLY THIS PART) -->
<div class="section how-section">
<h2>How It Works</h2>

<div class="timeline">

<div class="step">
<div class="step-number">1</div>
<div class="step-box">
<div class="step-title">Choose Event</div>
<div class="step-desc">Select your preferred event type to begin planning.</div>
</div>
</div>

<div class="step">
<div class="step-number">2</div>
<div class="step-box">
<div class="step-title">Select Package</div>
<div class="step-desc">Pick a package that fits your requirements.</div>
</div>
</div>

<div class="step">
<div class="step-number">3</div>
<div class="step-box">
<div class="step-title">Specify Capacity</div>
<div class="step-desc">Define number of guests attending the event.</div>
</div>
</div>

<div class="step">
<div class="step-number">4</div>
<div class="step-box">
<div class="step-title">Choose Venue</div>
<div class="step-desc">Select the perfect venue for your event.</div>
</div>
</div>

<div class="step">
<div class="step-number">5</div>
<div class="step-box">
<div class="step-title">Choose Decoration</div>
<div class="step-desc">Customize themes and decorations.</div>
</div>
</div>

<div class="step">
<div class="step-number">6</div>
<div class="step-box">
<div class="step-title">Choose Seats</div>
<div class="step-desc">Select seating arrangements.</div>
</div>
</div>

<div class="step">
<div class="step-number">7</div>
<div class="step-box">
<div class="step-title">Choose Food</div>
<div class="step-desc">Pick menu options for your guests.</div>
</div>
</div>

<div class="step">
<div class="step-number">8</div>
<div class="step-box">
<div class="step-title">Select Date</div>
<div class="step-desc">Finalize your event schedule.</div>
</div>
</div>

</div>
</div>

<!-- EVENT CATEGORIES -->
<!-- EVENT CATEGORIES -->
<div class="event-section">
    <h2>Event Categories</h2>

    <div class="event-grid">

        <!-- ANNUAL DAY -->
        <div class="event-card">
            <img src="uploads/images/annual/image1.webp">
            <div class="event-body">
                <h3>Annual Day</h3>
                <button onclick="location.href='events/annualday.php'">View Event</button>
            </div>
        </div>

        <!-- CONVOCATION -->
        <div class="event-card">
            <img src="uploads/images/convocation/convocation1.jpg">
            <div class="event-body">
                <h3>Convocation</h3>
                <button onclick="location.href='events/convocation.php'">View Event</button>
            </div>
        </div>

        <!-- FAREWELL -->
        <div class="event-card">
            <img src="uploads/images/farewell/farewell1.jpg">
            <div class="event-body">
                <h3>Farewell</h3>
                <button onclick="location.href='events/farewell.php'">View Event</button>
            </div>
        </div>

        <!-- FRESHERS -->
        <div class="event-card">
            <img src="uploads/images/freshers/fresher1.jpg">
            <div class="event-body">
                <h3>Freshers Party</h3>
                <button onclick="location.href='events/fresher.php'">View Event</button>
            </div>
        </div>

        <!-- SEMINAR -->
        <div class="event-card">
            <img src="uploads/images/seminar/s1.jpg">
            <div class="event-body">
                <h3>Seminar</h3>
                <button onclick="location.href='events/seminar.php'">View Event</button>
            </div>
        </div>

        <!-- SPORTS DAY -->
        <div class="event-card">
            <img src="uploads/images/sports day/sports day1.jpg">
            <div class="event-body">
                <h3>Sports Day</h3>
                <button onclick="location.href='events/sportsday.php'">View Event</button>
            </div>
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

<!-- CLIENT FEEDBACK -->
<div class="feedback-section">
    <h2>What Our Clients Say</h2>

    <div class="feedback-grid">

        <?php while($f=mysqli_fetch_assoc($result)){ ?>

        <div class="feedback-card">
            
            <div class="stars">
                ⭐ <?= $f['rating'] ?>/5
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

</body>
</html>