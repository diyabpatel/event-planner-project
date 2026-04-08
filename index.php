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
.slide:nth-child(1){background-image:url('uploads/images/annual/annualday_bg02.jpg'); animation-delay:0s;}
.slide:nth-child(2){background-image:url('uploads/images/convocation/convocation1.jpg'); animation-delay:3s;}
.slide:nth-child(3){background-image:url('uploads/images/farewell/farewell2.jpg'); animation-delay:6s;}
.slide:nth-child(4){background-image:url('uploads/images/freshers/fresher5.jpg'); animation-delay:9s;}
.slide:nth-child(5){background-image:url('uploads/images/seminar/s2.jpeg'); animation-delay:12s;}
.slide:nth-child(6){background-image:url('uploads/images/sports day/sports day1.jpg'); animation-delay:15s;}

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
/* 🔥 FULL SECTION FIXED */
.how-section{
    min-height:100vh;                 /* 👈 important fix */
    display:flex;
    align-items:flex-start;           /* 👈 removes vertical centering */
    justify-content:space-between;
    padding:80px 6%;                  /* 👈 controlled spacing */
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
}

/* LEFT SIDE */
.how-left{
    width:40%;
    .how-left{
    width:40%;
    display:flex;
    flex-direction:column;
    justify-content:center;   /* 👈 centers left content */
}
}

.how-left h2{
    font-size:34px;
    color:#3b0764;
    margin-bottom:15px;
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

/* 🔥 RIGHT SIDE GRID */
/* 🔥 RIGHT SIDE GRID (FIXED) */
.timeline{
    width:55%;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;                  /* 👈 more spacing */
    align-content:center;      /* 👈 center vertically (IMPORTANT) */
}

/* STEP */
.step{
    display:flex;
    align-items:flex-start;
    gap:14px;
}

/* NUMBER */
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
}

/* 🔥 BIGGER CARD (MAIN FIX) */
.step-box{
    background:#ffffff;
    padding:18px;              /* 👈 increased */
    border-radius:14px;
    border:1px solid #ede9fe;
    transition:0.3s;
    width:100%;
    min-height:85px;           /* 👈 forces height */
    display:flex;
    flex-direction:column;
    justify-content:center;
}

/* HOVER */
.step-box:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 30px rgba(124,58,237,0.2);
}

/* TITLE */
.step-title{
    color:#4c1d95;
    font-size:16px;            /* 👈 bigger */
    font-weight:600;
    margin-bottom:6px;
}

/* DESC */
.step-desc{
    color:#6b7280;
    font-size:13px;
}

/* 📱 RESPONSIVE */
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

/* ===== EVENT CATEGORIES ===== */
/* SECTION */
/* 🔥 FULL SCREEN SECTION */
.event-section{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 6%;
    background:linear-gradient(135deg,#ffffff,#f5f3ff);
}

/* LEFT GRID */
.event-grid{
    width:55%;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

/* CARD */
.event-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
    text-align:center;
}

/* IMAGE */
.event-card img{
    width:100%;
    height:130px; /* 👈 reduced to fit screen */
    object-fit:cover;
}

/* TITLE */
.event-card h3{
    padding:10px;
    color:#4c1d95;
    font-size:14px;
}

/* HOVER */
.event-card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 35px rgba(124,58,237,0.2);
}

/* RIGHT TEXT */
.event-text{
    width:40%;
}

/* HEADING */
.event-text h2{
    font-size:34px;
    color:#3b0764;
    margin-bottom:15px;
}

/* TAGLINE */
.tagline{
    color:#7c3aed;
    font-weight:600;
    margin-bottom:15px;
    font-size:16px;
}

/* DESCRIPTION */
.desc{
    color:#6b7280;
    margin-bottom:20px;
    line-height:1.6;
}

/* FEATURES */
.features{
    margin-bottom:20px;
}

.features div{
    margin-bottom:8px;
    color:#4c1d95;
    font-size:14px;
}

/* BUTTON */
.explore-btn{
    padding:12px 22px;
    border:none;
    background:#7c3aed;
    color:#fff;
    border-radius:25px;
    cursor:pointer;
    font-weight:600;
}

/* RESPONSIVE */
@media(max-width:900px){
    .event-section{
        flex-direction:column;
        height:auto;
        padding:50px 5%;
    }

    .event-grid,
    .event-text{
        width:100%;
    }
}
.event-card{
    text-decoration:none;   /* ❗ removes underline */
    color:inherit;          /* ❗ keeps text color */
    display:block;          /* ❗ makes full card clickable */
}

/* ===== FEEDBACK===== */
/* SECTION */
.feedback-section{
    padding:80px 8%;
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

<!-- EVENT CATEGORIES -->
<div class="event-section">

    <!-- LEFT SIDE (EVENTS) -->
    <div class="event-grid">

    <a href="events/annualday.php" class="event-card">
        <img src="uploads/images/annual/annualday_bg03.jpg">
        <h3>Annual Day</h3>
    </a>

    <a href="events/convocation.php" class="event-card">
        <img src="uploads/images/convocation/convocation1.jpg">
        <h3>Convocation</h3>
    </a>

    <a href="events/farewell.php" class="event-card">
        <img src="uploads/images/farewell/farewell1.jpg">
        <h3>Farewell</h3>
    </a>

    <a href="events/fresher.php" class="event-card">
        <img src="uploads/images/freshers/fresher1.jpg">
        <h3>Freshers</h3>
    </a>

    <a href="events/seminar.php" class="event-card">
        <img src="uploads/images/seminar/s1.jpg">
        <h3>Seminar</h3>
    </a>

    <a href="events/sportsday.php" class="event-card">
        <img src="uploads/images/sports day/sports day1.jpg">
        <h3>Sports Day</h3>
    </a>

</div>
    <!-- RIGHT SIDE (TEXT) -->
    <div class="event-text">

        <h2>Crafting Memorable College Experiences</h2>

        <p class="tagline">
            “From planning to perfection — we bring your events to life.”
        </p>

        <p class="desc">
            Whether it’s a grand Annual Day, an emotional Farewell, or a professional Seminar,
            our platform helps you organize events seamlessly. We provide end-to-end solutions
            including venue selection, decoration, catering, and execution.
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
<div class="how-section">

    <!-- LEFT SIDE (TEXT) -->
    <div class="how-left">

        <h2>How EventHub Works</h2>

        <p class="how-tagline">
            “Plan smarter. Execute better. Celebrate bigger.”
        </p>

        <p class="how-desc">
            EventHub simplifies the entire event planning process for colleges.
            From selecting event types to final execution, everything is handled
            in a structured and seamless way.
        </p>

        <div class="how-points">
            <div>✔ Simple step-by-step process</div>
            <div>✔ Fully customizable options</div>
            <div>✔ End-to-end event management</div>
            <div>✔ Designed for students & organizers</div>
        </div>

    </div>

    <!-- RIGHT SIDE (STEPS) -->
    <div class="timeline">

        <div class="step">
            <div class="step-number">1</div>
            <div class="step-box">
                <div class="step-title">Choose Event</div>
                <div class="step-desc">Select your preferred event type.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">2</div>
            <div class="step-box">
                <div class="step-title">Select Package</div>
                <div class="step-desc">Pick a package that suits you.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">3</div>
            <div class="step-box">
                <div class="step-title">Capacity</div>
                <div class="step-desc">Define number of guests.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">4</div>
            <div class="step-box">
                <div class="step-title">Venue</div>
                <div class="step-desc">Choose your event location.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">5</div>
            <div class="step-box">
                <div class="step-title">Decoration</div>
                <div class="step-desc">Customize themes & decor.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">6</div>
            <div class="step-box">
                <div class="step-title">Seating</div>
                <div class="step-desc">Arrange seating style.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">7</div>
            <div class="step-box">
                <div class="step-title">Food</div>
                <div class="step-desc">Select menu options.</div>
            </div>
        </div>

        <div class="step">
            <div class="step-number">8</div>
            <div class="step-box">
                <div class="step-title">Date</div>
                <div class="step-desc">Finalize your schedule.</div>
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