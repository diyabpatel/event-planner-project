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

/* DARK OVERLAY */

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
font-size:65px;
font-weight:bold;
letter-spacing:2px;
text-shadow:0 8px 30px rgba(0,0,0,0.9);
}

/* BUTTON */

.hero button{
margin-top:25px;
padding:15px 35px;
font-size:18px;
background:#ff4b2b;
border:none;
color:white;
cursor:pointer;
border-radius:5px;
transition:0.3s;
}

.hero button:hover{
background:#ff2e00;
transform:scale(1.05);
}

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

.about p{
line-height:1.6;
color:#444;
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
transition:0.3s;
}

.service-card:hover{
transform:translateY(-8px);
}

.service-card img{
width:100%;
height:200px;
object-fit:cover;
border-radius:6px;
}

/* GALLERY */

.gallery{
margin-top:80px;
}

.gallery h2{
text-align:center;
margin-bottom:40px;
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
border-radius:10px;
transition:0.4s;
}

.gallery-grid img:hover{
transform:scale(1.05);
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

/* FOOTER */

footer{
background:black;
color:white;
padding:40px;
margin-top:80px;
text-align:center;
}

/* RESPONSIVE */

@media(max-width:900px){

.about{
flex-direction:column;
text-align:center;
}

.about img{
width:100%;
}

.service-grid{
grid-template-columns:1fr;
}

.gallery-grid{
grid-template-columns:1fr;
}

.hero-content{
font-size:40px;
}

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

<img src="../uploads/images/freshers/freshers_about.jpg">

<div>

<h2>About Freshers Party</h2>

<p>

Freshers Party is a welcoming event organized to greet the new students in college.  
The event includes DJ night, dance performances, fun games, fashion shows, and entertainment activities.

Our event management team provides full services including venue setup, decoration, lighting, sound system, and photography.

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
<img src="../uploads/images/freshers/sound.jpg">
<h3>Sound & Lighting</h3>
</div>

</div>

</div>



<!-- GALLERY -->

<div class="gallery">

<h2>Our Previous Works</h2>

<div class="gallery-grid">

<img src="../uploads/images/freshers/fresher1.jpg">
<img src="../uploads/images/freshers/fresher2.jpg">
<img src="../uploads/images/freshers/fresher3.jpg">
<img src="../uploads/images/freshers/fresher4.jpg">
<img src="../uploads/images/freshers/fresher5.jpg">
<img src="../uploads/images/freshers/fresher7.jpg">

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