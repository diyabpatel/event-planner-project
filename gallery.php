<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Gallery</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

/* BACKGROUND */

body{
background:linear-gradient(135deg,#ffd6ec,#d6f0ff,#ffe8d6);
min-height:100vh;
margin:0;
 
}

/* TITLE */

.title{
text-align:center;
font-size:42px;
margin-bottom:50px;
color:black;
letter-spacing:2px;
font-weight:600;
padding: 20px;
}

/* MASONRY STYLE */

.gallery{
column-count:3;
column-gap:25px;
max-width:1300px;
margin:auto;
}

@media(max-width:900px){
.gallery{column-count:2;}
}

@media(max-width:600px){
.gallery{column-count:1;}
}

/* CARD */

.card{
break-inside:avoid;
margin-bottom:25px;
background:rgba(255,255,255,0.8);
border-radius:18px;
padding:12px;
box-shadow:0 10px 30px rgba(0,0,0,0.15);
transition:0.4s;
overflow:hidden;
}

.card:hover{
transform:translateY(-8px) scale(1.02);
box-shadow:0 20px 40px rgba(0,0,0,0.25);
}

/* IMAGE GRID */

.card a{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:6px;
text-decoration:none;
}

/* IMAGES */

.card img{
width:100%;
height:110px;
object-fit:cover;
border-radius:8px;
transition:0.4s;
}

.card img:hover{
transform:scale(1.1);
}

/* EVENT TITLE */

.card h3{
grid-column:1/4;
text-align:center;
padding:12px;
font-size:20px;
color:#444;
font-weight:500;
}

/* SOFT PASTEL GLOW */

.card{
border:2px solid transparent;
background-clip:padding-box;
}

.card:hover{
border:2px solid #ffb6e6;
}

</style>
</head>

<body>

<?php include("/navbar.php"); ?>


<h1 class="title">College Event Gallery</h1>

<div class="gallery">

<!-- ANNUAL DAY -->
<div class="card">
<a href="events/annualday.php">
<img src="uploads/images/annual/image1.webp">
<img src="uploads/images/annual/image2.jpg">
<img src="uploads/images/annual/image3.jpg">
<img src="uploads/images/annual/image4.jpg">
<img src="uploads/images/annual/image5.jpg">
<img src="uploads/images/annual/image6.jpg">
<h3>Annual Day</h3>
</a>
</div>

<!-- CONVOCATION -->
<div class="card">
<a href="events/convocation.php">
<img src="uploads/images/convocation/convocation1.jpg">
<img src="uploads/images/convocation/convocation2.jpg">
<img src="uploads/images/convocation/convocation3.jpg">
<img src="uploads/images/convocation/convocation4.jpg">
<img src="uploads/images/convocation/convocation5.jpg">
<img src="uploads/images/convocation/convocation6.jpg">
<h3>Convocation</h3>
</a>
</div>

<!-- FAREWELL -->
<div class="card">
<a href="events/farewell.php">
<img src="uploads/images/farewell/farewell1.jpg">
<img src="uploads/images/farewell/farewell2.jpg">
<img src="uploads/images/farewell/farewell3.jpg">
<img src="uploads/images/farewell/farewell4.jpg">
<img src="uploads/images/farewell/farewell5.jpg">
<img src="uploads/images/farewell/farewell6.jpeg">
<h3>Farewell Party</h3>
</a>
</div>

<!-- FRESHERS -->
<div class="card">
<a href="events/fresher.php">
<img src="uploads/images/freshers/fresher1.jpg">
<img src="uploads/images/freshers/fresher2.jpg">
<img src="uploads/images/freshers/fresher3.jpg">
<img src="uploads/images/freshers/fresher4.jpg">
<img src="uploads/images/freshers/fresher5.jpg">
<img src="uploads/images/freshers/fresher7.jpg">
<h3>Freshers Party</h3>
</a>
</div>

<!-- SEMINAR -->
<div class="card">
<a href="events/seminar.php">
<img src="uploads/images/seminar/s1.jpg">
<img src="uploads/images/seminar/s2.jpeg">
<img src="uploads/images/seminar/s3.jpeg">
<img src="uploads/images/seminar/s4.jpeg">
<img src="uploads/images/seminar/s5.jpeg">
<img src="uploads/images/seminar/s6.jpeg">
<h3>Seminar</h3>
</a>
</div>

<!-- SPORTS DAY -->
<div class="card">
<a href="events/sportsday.php">
<img src="uploads/images/sports day/sports day1.jpg">
<img src="uploads/images/sports day/sports day2.jpg">
<img src="uploads/images/sports day/sports day3.jpg">
<img src="uploads/images/sports day/sports day4.jpg">
<img src="uploads/images/sports day/sports day5.jpg">
<img src="uploads/images/sports day/sports day6.jpg">
<h3>Sports Day</h3>
</a>
</div>

</div>

</body>
</html>