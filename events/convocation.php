<?php
session_start();
include("../db.php");

$event = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM events WHERE event_name='Convocation'")
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Convocation Ceremony</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:
        linear-gradient(rgba(10,25,60,0.75), rgba(10,25,60,0.85)),
        url('https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1600&q=80')
        center/cover no-repeat;
    color:white;
}

/* HERO */
.hero{
    text-align:center;
    padding:110px 20px 90px;
}

.hero h1{
    font-size:48px;
    font-weight:600;
    margin-bottom:15px;
}

.hero p{
    font-size:16px;
    max-width:650px;
    margin:auto;
    color:#e2e8f0;
}

/* SECTION */
.section{
    max-width:1150px;
    margin:60px auto;
    padding:45px;
    border-radius:25px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(20px);
    box-shadow:0 25px 70px rgba(0,0,0,0.5);
}

.section h2{
    margin-bottom:35px;
    font-size:30px;
    font-weight:500;
    color:#dbeafe;
    text-align:center;
}

/* IMAGE GRID */
.details{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:30px;
}

/* IMAGE CARD */
.card{
    position:relative;
    border-radius:20px;
    overflow:hidden;
    cursor:pointer;
    transition:0.4s ease;
}

.card img{
    width:100%;
    height:260px;
    object-fit:cover;
    transition:0.5s ease;
}

.card-content{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    padding:20px;
    background:linear-gradient(to top,rgba(0,0,0,0.85),transparent);
}

.card h3{
    margin:0;
    font-size:18px;
}

.card p{
    margin-top:6px;
    font-size:13px;
    color:#e5e7eb;
}

.card:hover img{
    transform:scale(1.1);
}

.card:hover{
    transform:translateY(-8px);
}

/* BUTTON */
.book-btn{
    display:inline-block;
    margin-top:50px;
    padding:16px 45px;
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    border-radius:30px;
    text-decoration:none;
    color:white;
    font-weight:500;
    font-size:16px;
    box-shadow:0 15px 45px rgba(37,99,235,0.5);
    transition:0.3s;
}

.book-btn:hover{
    transform:translateY(-5px);
    box-shadow:0 25px 60px rgba(37,99,235,0.7);
}

/* RESPONSIVE */
@media(max-width:900px){
    .details{
        grid-template-columns:1fr;
    }
    .hero h1{
        font-size:34px;
    }
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->
<div class="hero">
    <h1>Convocation Ceremony 2025</h1>
    <p>
        Celebrate achievements, honor excellence, and create unforgettable memories 
        with a grand and elegant convocation event tailored perfectly for your institution.
    </p>
</div>

<!-- HIGHLIGHTS -->
<div class="section">
    <h2>Event Highlights</h2>

    <div class="details">

        <div class="card">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>🎓 Grand Stage Setup</h3>
                <p>Elegant stage design with professional lighting and branding.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1596495578065-6e0763fa1178?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>🏅 Degree Distribution</h3>
                <p>Memorable certificate and medal presentation ceremony.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>📸 Celebration Moments</h3>
                <p>Professional photography and joyful group captures.</p>
            </div>
        </div>

    </div>

    <div style="text-align:center;">
        <a href="../User/book_event.php?event_id=<?= $event['event_id'] ?>" class="book-btn">
            Book Convocation Now
        </a>
    </div>

</div>

</body>
</html>