<?php
session_start();
include("../db.php");

$event = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM events WHERE event_name='Farewell'")
);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Farewell Party</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{box-sizing:border-box;}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:
        linear-gradient(rgba(20,20,60,0.75), rgba(20,20,60,0.85)),
        url('https://images.unsplash.com/photo-1520975922284-7b9585a6f6b6?auto=format&fit=crop&w=1600&q=80')
        center/cover no-repeat;
    color:white;
}

/* HERO */
.hero{
    text-align:center;
    padding:120px 20px 90px;
}
.hero h1{
    font-size:48px;
    font-weight:600;
}
.hero p{
    max-width:700px;
    margin:15px auto 0;
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
    margin-bottom:40px;
    color:#dbeafe;
}

/* GRID */
.details{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:30px;
}

.card{
    position:relative;
    overflow:hidden;
    border-radius:22px;
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
    width:100%;
    padding:22px;
    background:linear-gradient(to top,rgba(0,0,0,0.85),transparent);
}
.card:hover img{transform:scale(1.1);}
.card:hover{transform:translateY(-8px);}

/* BUTTON */
.book-btn{
    display:inline-block;
    margin-top:55px;
    padding:17px 48px;
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    border-radius:35px;
    color:white;
    text-decoration:none;
    box-shadow:0 15px 45px rgba(37,99,235,0.5);
    transition:0.3s;
}
.book-btn:hover{
    transform:translateY(-5px);
}

@media(max-width:900px){
    .details{grid-template-columns:1fr;}
    .hero h1{font-size:34px;}
}
</style>
</head>

<body>
<?php include("../navbar.php"); ?>

<div class="hero">
    <h1>Farewell Party 2025</h1>
    <p>A beautiful goodbye filled with memories, laughter, emotions and celebration.</p>
</div>

<div class="section">
    <h2>Farewell Highlights</h2>

    <div class="details">
        <div class="card">
            <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>🎤 Speeches & Memories</h3>
                <p>Heartfelt moments shared by juniors & faculty.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1521335629791-ce4aec67dd47?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>💃 Dance & Performances</h3>
                <p>Special farewell performances for seniors.</p>
            </div>
        </div>

        <div class="card">
            <img src="https://images.unsplash.com/photo-1515165562835-c3b8c38b0e96?auto=format&fit=crop&w=900&q=80">
            <div class="card-content">
                <h3>📸 Memories Forever</h3>
                <p>Group photos and unforgettable moments.</p>
            </div>
        </div>
    </div>

    <div style="text-align:center;">
        <a href="../User/book_event.php?event_id=<?= $event['event_id'] ?>" class="book-btn">
            Book Farewell Now
        </a>
    </div>
</div>

</body>
</html>