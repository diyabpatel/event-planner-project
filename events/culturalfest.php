<?php
session_start();
include("../db.php");

$event = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM events WHERE event_name='Cultural Fest'")
);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cultural Fest</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:
        linear-gradient(rgba(20,20,60,0.75), rgba(20,20,60,0.85)),
        url('https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1600&q=80')
        center/cover no-repeat;
    color:#fff;
}

/* HERO */
.hero{
    text-align:center;
    padding:120px 20px 90px;
}

.hero h1{
    font-size:52px;
    font-weight:600;
    margin-bottom:15px;
}

.hero p{
    font-size:17px;
    max-width:700px;
    margin:auto;
    color:#e5e7eb;
}

/* SECTION */
.section{
    max-width:1200px;
    margin:60px auto;
    padding:45px;
    border-radius:26px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(20px);
    box-shadow:0 25px 70px rgba(0,0,0,0.5);
}

.section h2{
    text-align:center;
    font-size:30px;
    font-weight:500;
    margin-bottom:40px;
    color:#dbeafe;
}

/* HIGHLIGHTS GRID */
.details{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:30px;
}

/* CARD */
.card{
    position:relative;
    overflow:hidden;
    border-radius:22px;
    cursor:pointer;
    transition:0.4s;
}

.card img{
    width:100%;
    height:260px;
    object-fit:cover;
    transition:0.5s;
}

.card-content{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    padding:22px;
    background:linear-gradient(to top,rgba(0,0,0,0.85),transparent);
}

.card h3{
    margin:0;
    font-size:20px;
    font-weight:500;
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

/* CTA */
.book-btn{
    display:inline-block;
    margin-top:55px;
    padding:17px 48px;
    background:linear-gradient(135deg,#6366f1,#3b82f6);
    border-radius:35px;
    text-decoration:none;
    color:white;
    font-size:16px;
    font-weight:500;
    box-shadow:0 15px 45px rgba(99,102,241,0.5);
    transition:0.3s;
}

.book-btn:hover{
    transform:translateY(-5px);
    box-shadow:0 25px 60px rgba(99,102,241,0.7);
}

/* RESPONSIVE */
@media(max-width:900px){
    .details{
        grid-template-columns:1fr;
    }
    .hero h1{
        font-size:36px;
    }
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->
<div class="hero">
    <h1>Cultural Fest 2025</h1>
    <p>
        Experience the spirit of creativity, music, dance, drama, and celebration
        with a high-energy cultural fest crafted to bring students together.
    </p>
</div>

<!-- HIGHLIGHTS -->
<div class="section">
    <h2>Fest Highlights</h2>

    <div class="details">

        <div class="card">
            <img src="https://images.unsplash.com/photo-1518972559570-7cc1309f3229?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>🎶 Live Music & Bands</h3>
                <p>High-energy performances by college and guest bands.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1508804185872-d7badad00f7d?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>💃 Dance & Drama</h3>
                <p>Solo, group dance and theatrical performances.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1497032205916-ac775f0649ae?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>🎉 DJ Night & Celebration</h3>
                <p>Grand DJ night with lights, sound and crowd energy.</p>
            </div>
        </div>

    </div>

    <div style="text-align:center;">
        <a href="../User/book_event.php?event_id=<?= $event['event_id'] ?>" class="book-btn">
            Book Cultural Fest Now
        </a>
    </div>
</div>

</body>
</html>