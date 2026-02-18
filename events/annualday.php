<?php
session_start();
include("../db.php");

// fetch Annual Day event
$query = "SELECT * FROM events WHERE event_name='Annual Day'";
$result = mysqli_query($conn,$query);
$event = mysqli_fetch_assoc($result);

$event_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Annual Day Event</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f8;
}

header{
background:#2c3e50;
color:white;
padding:15px;
}

header h1{
display:inline-block;
margin:0;
}

nav{
float:right;
}

nav a{
color:white;
margin-left:20px;
text-decoration:none;
}

.hero{
background-image:url("../uploads/images/annual/annualday_bg.png");
height:700px;
background-size:cover;
background-position:center;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

.hero-content{
color:white;
font-size:50px;
font-weight:bold;
}

.hero button{
margin-top:20px;
padding:15px 30px;
font-size:18px;
background:#e74c3c;
border:none;
color:white;
cursor:pointer;
}

.container{
padding:60px;
}

.about{
display:flex;
gap:40px;
align-items:center;
}

.about img{
width:500px;
border-radius:10px;
}

.services{
margin-top:60px;
}

.service-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:30px;
}

.service-card{
background:white;
padding:20px;
border-radius:10px;
text-align:center;
box-shadow:0px 0px 10px gray;
}

.service-card img{
width:100%;
height:200px;
object-fit:cover;
}

.gallery{
margin-top:60px;
}

.gallery-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.gallery-grid img{
width:100%;
height:200px;
object-fit:cover;
}

.packages{
margin-top:60px;
}

.package-grid{
display:flex;
gap:30px;
}

.package-card{
background:white;
padding:20px;
border-radius:10px;
width:250px;
box-shadow:0px 0px 10px gray;
}

.package-card button{
margin-top:10px;
padding:10px;
width:100%;
background:#3498db;
color:white;
border:none;
cursor:pointer;
}

footer{
background:black;
color:white;
padding:40px;
margin-top:60px;
text-align:center;
}

</style>

</head>

<body>


<header>

<h1>Event Management</h1>

<nav>

<a href="../index.php">Home</a>

<?php
if(isset($_SESSION['user_id']))
{
echo '<a href="../user/MyBookings.php">My Bookings</a>';
echo '<a href="../user/logout.php">Logout</a>';
}
else
{
echo '<a href="../login.php">Login</a>';
}
?>

</nav>

<div style="clear:both;"></div>

</header>



<div class="hero">

<div class="hero-content">

Annual Day Event

<br>

<?php
if(isset($_SESSION['user_id']))
{
echo "<button onclick=\"location.href='../user/book_event.php?event_id=$event_id'\">Book Now</button>";
}
else
{
echo "<button onclick=\"location.href='../login.php'\">Login to Book</button>";
}
?>

</div>

</div>



<div class="container">


<div class="about">

<img src="../uploads/images/annual/annualday_about.jpg">

<div>

<h2>About Annual Day</h2>

<p>

Annual Day is the biggest celebration event in college including cultural performances, awards, and guest appearances.

We provide complete event management including venue, catering, decoration, photography, videography, and sound systems.

</p>

</div>

</div>



<div class="services">

<h2>Services Provided</h2>

<div class="service-grid">

<div class="service-card">
<img src="../uploads/images/annual/venue.png">
<h3>Venue Setup</h3>
</div>

<div class="service-card">
<img src="../uploads/images/annual/decoration.png">
<h3>Decoration</h3>
</div>

<div class="service-card">
<img src="../uploads/images/annual/catering.png">
<h3>Catering</h3>
</div>

<div class="service-card">
<img src="../uploads/images/annual/photography.png">
<h3>Photography</h3>
</div>

<div class="service-card">
<img src="../uploads/images/annual/videography.jpg">
<h3>Videography</h3>
</div>

<div class="service-card">
<img src="../uploads/images/annual/sound.png">
<h3>Sound & Lighting</h3>
</div>

</div>

</div>



<div class="gallery">

<h2>Our Previous Works</h2>

<div class="gallery-grid">

<img src="../uploads/images/annual/image1.webp">
<img src="../uploads/images/annual/image2.jpg">
<img src="../uploads/images/annual/image3.jpg">
<img src="../uploads/images/annual/image4.jpg">
<img src="../uploads/images/annual/image5.jpg">
<img src="../uploads/images/annual/image6.jpg">

</div>

</div>



</div>



<footer>

Event Management System

</footer>


</body>
</html>
