<?php
session_start();
include("../db.php");

/* ===== FETCH EVENT ===== */

$query = "SELECT * FROM events WHERE event_name='Freshers Party'";
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
<title>Freshers Party Event</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f8;
}

/* HERO */

.hero{
position:relative;
background-image:url("../uploads/images/freshers/fresher_bg.jpg");
height:700px;
background-size:cover;
background-position:center;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
}

.hero::before{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.55);
}

.hero-content{
position:relative;
color:white;
font-size:60px;
font-weight:bold;
}

.hero button{
margin-top:20px;
padding:15px 35px;
font-size:18px;
background:#ff4b2b;
border:none;
color:white;
cursor:pointer;
}

/* CONTAINER */

.container{
padding:70px;
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
margin-top:70px;
}

.services h2{
text-align:center;
margin-bottom:40px;
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
box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.service-card img{
width:100%;
height:200px;
object-fit:cover;
}

/* GALLERY */

.gallery{
margin-top:80px;
}

.gallery-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.gallery-grid img{
width:100%;
height:220px;
object-fit:cover;
}

/* FEEDBACK */

.feedback-section{
margin-top:100px;
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
color:#3498db;
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

footer{
background:black;
color:white;
padding:40px;
margin-top:80px;
text-align:center;
}

</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<!-- HERO -->

<div class="hero">

<div class="hero-content">

Freshers Party

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

<img src="../uploads/images/freshers/fresher.webp">

<div>

<h2>About Freshers Party</h2>

<p>

Freshers Party is organized to welcome new students to the college.  
It includes DJ nights, dance performances, fashion shows, and fun activities.

Our event management team provides full services including venue setup,
decoration, sound system, photography, and lighting.

</p>

</div>

</div>


<!-- SERVICES -->

<div class="services">

<h2>Services Provided</h2>

<div class="service-grid">

<div class="service-card">
<img src="../uploads/images/freshers/venue.jpg">
<h3>Venue Setup</h3>
</div>

<div class="service-card">
<img src="../uploads/images/freshers/catering.jpg">
<h3>Catering</h3>
</div>

<div class="service-card">
<img src="../uploads/images/freshers/sound.jpg">
<h3>DJ Night</h3>
</div>

<div class="service-card">
<img src="../uploads/images/freshers/decoration.jpg">
<h3>Decoration</h3>
</div>

<div class="service-card">
<img src="../uploads/images/freshers/photography.jpg">
<h3>Photography</h3>
</div>

<div class="service-card">
<img src="../uploads/images/freshers/videography.jpg">
<h3>Videography</h3>
</div>

</div>

</div>


<!-- GALLERY -->

<div class="gallery">

<h2>Freshers Party Moments</h2>

<div class="gallery-grid">

<img src="../uploads/images/freshers/fresher1.png">
<img src="../uploads/images/freshers/fresher2.png">
<img src="../uploads/images/freshers/fresher3.png">
<img src="../uploads/images/freshers/fresher4.png">
<img src="../uploads/images/freshers/fresher5.png">
<img src="../uploads/images/freshers/fresher6.png">

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