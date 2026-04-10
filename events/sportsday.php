<?php
session_start();
include("../db.php");

/* ===== FETCH EVENT ===== */

$query = "SELECT * FROM events WHERE event_name='Sports Day'";
$result = mysqli_query($conn,$query);
$event = mysqli_fetch_assoc($result);

$event_id = $event['event_id'];

/* ===== FETCH FEEDBACK ===== */

$feedback_query = mysqli_query($conn,"
SELECT f.rating,f.comment,u.college_name
FROM feedback f
JOIN bookings b ON f.booking_id=b.booking_id
JOIN users u ON f.user_id=u.user_id
WHERE b.event_id='$event_id'
ORDER BY f.created_at DESC
LIMIT 6
");

if(!$feedback_query){
$feedback_error = mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Sports Day Event</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f8;
}

/* HERO */

.hero{
background:
linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),
url("../uploads/images/sports day/sports_day_bg.jpg");
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
background:#16a34a;
border:none;
color:white;
cursor:pointer;
}

/* CONTAINER */

.container{
padding:60px;
}

/* ABOUT */

.about{
display:flex;
gap:40px;
align-items:center;
}

.about img{
width:500px;
border-radius:10px;
}

/* SERVICES */

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

/* GALLERY */

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

/* FEEDBACK */

.feedback-section{
margin-top:80px;
text-align:center;
}

.feedback-grid{
margin-top:40px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

.feedback-card{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.15);
position:relative;
}

.feedback-card:before{
content:"“";
font-size:60px;
color:#16a34a;
position:absolute;
top:-10px;
left:15px;
}

.feedback-stars{
color:#facc15;
font-size:18px;
margin:10px 0;
}

.feedback-user{
margin-top:15px;
font-weight:bold;
color:#333;
}

/* FOOTER */

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

<?php include("../navbar.php"); ?>

<!-- HERO -->

<div class="hero">

<div class="hero-content">

Sports Day

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


<!-- ABOUT -->

<div class="about">

<img src="../uploads/images/sports day/sports_day_about.png">

<div>

<h2>About Sports Day</h2>

<p>

Sports Day celebrates athletic spirit, teamwork, and competition.
Students participate in races, games, and exciting sports activities.

Our event management includes venue setup, decoration, food stalls,
photography, and complete sports event coordination.

</p>

</div>

</div>



<!-- SERVICES -->

<div class="services">

<h2>Services Provided</h2>

<div class="service-grid">

<div class="service-card">
<img src="../uploads/images/sports day/venue.png">
<h3>Venue Setup</h3>
</div>

<div class="service-card">
<img src="../uploads/images/sports day/decoration.png">
<h3>Decoration</h3>
</div>

<div class="service-card">
<img src="../uploads/images/sports day/prize_distribution.png">
<h3>Price Distribution</h3>
</div>

<div class="service-card">
<img src="../uploads/images/sports day/food_stalls.png">
<h3>Food Stalls</h3>
</div>

<div class="service-card">
<img src="../uploads/images/sports day/photography.png">
<h3>Photography</h3>
</div>

<div class="service-card">
<img src="../uploads/images/sports day/videography.png">
<h3>Videography</h3>
</div>

</div>

</div>



<!-- GALLERY -->

<div class="gallery">

<h2>Sports Day Moments</h2>

<div class="gallery-grid">

<img src="../uploads/images/sports day/sports_day1.png">
<img src="../uploads/images/sports day/sports_day2.png">
<img src="../uploads/images/sports day/sports_day3.png">
<img src="../uploads/images/sports day/sports_day4.png">
<img src="../uploads/images/sports day/sports_day5.png">
<img src="../uploads/images/sports day/sports_day2.png">

</div>

</div>



<!-- FEEDBACK -->

<div class="feedback-section">

<h2>What Our Customers Say</h2>

<div class="feedback-grid">

<?php

if(isset($feedback_error)){
echo "<p>Feedback Error: $feedback_error</p>";
}

else if(mysqli_num_rows($feedback_query)>0){

while($f=mysqli_fetch_assoc($feedback_query)){

$stars = str_repeat("⭐",$f['rating']);

echo "

<div class='feedback-card'>

<div class='feedback-stars'>$stars</div>

<p>".$f['comment']."</p>

<div class='feedback-user'>- ".$f['college_name']."</div>

</div>

";

}

}

else{
echo "<p>No feedback available yet.</p>";
}

?>

</div>

</div>

</div>



<footer>
Event Management System
</footer>

</body>
</html>