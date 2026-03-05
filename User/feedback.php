<?php

session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
header("Location:../login.php");
exit();
}

$user_id=$_SESSION['user_id'];
$booking_id=(int)$_GET['booking_id'];

if(isset($_POST['submit'])){

$rating=$_POST['rating'];
$comment=$_POST['comment'];

mysqli_query($conn,"
INSERT INTO feedback(booking_id,user_id,rating,comment)
VALUES('$booking_id','$user_id','$rating','$comment')
");

echo "<script>
alert('Thank you for your feedback');
window.location='my_bookings.php';
</script>";

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Event Feedback</title>

<style>

body{
font-family:Segoe UI,Arial;
background:#f4f6f8;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
margin:0;
}

/* CARD */

.card{
background:white;
padding:35px;
width:420px;
border-radius:12px;
box-shadow:0 12px 30px rgba(0,0,0,0.2);
text-align:center;
}

h2{
margin-bottom:20px;
}

/* STAR RATING */

.stars{
display:flex;
justify-content:center;
gap:10px;
font-size:36px;
cursor:pointer;
margin:15px 0;
}

.star{
color:#d1d5db;
transition:0.2s;
}

.star:hover,
.star.hover{
color:#facc15;
}

.star.active{
color:#facc15;
}

/* TEXTAREA */

textarea{
width:100%;
padding:12px;
margin-top:10px;
border-radius:6px;
border:1px solid #ccc;
resize:none;
}

/* BUTTON */

button{
width:100%;
padding:12px;
margin-top:18px;
background:#007bff;
border:none;
color:white;
border-radius:6px;
cursor:pointer;
font-size:15px;
}

button:hover{
background:#0056b3;
}

</style>

</head>

<body>

<div class="card">

<h2>Give Feedback</h2>

<form method="POST">

<label>Rating</label>

<div class="stars">

<span class="star" data-value="1">&#9733;</span>
<span class="star" data-value="2">&#9733;</span>
<span class="star" data-value="3">&#9733;</span>
<span class="star" data-value="4">&#9733;</span>
<span class="star" data-value="5">&#9733;</span>

</div>

<input type="hidden" name="rating" id="rating" required>

<label>Comment</label>

<textarea name="comment" rows="4" placeholder="Write your experience"></textarea>

<button name="submit">Submit Feedback</button>

</form>

</div>

<script>

const stars=document.querySelectorAll(".star");
const ratingInput=document.getElementById("rating");

/* CLICK RATING */

stars.forEach((star,index)=>{

star.addEventListener("click",function(){

let value=index+1;

ratingInput.value=value;

stars.forEach(s=>s.classList.remove("active"));

for(let i=0;i<value;i++){
stars[i].classList.add("active");
}

});

});

/* HOVER EFFECT */

stars.forEach((star,index)=>{

star.addEventListener("mouseover",function(){

stars.forEach(s=>s.classList.remove("hover"));

for(let i=0;i<=index;i++){
stars[i].classList.add("hover");
}

});

star.addEventListener("mouseout",function(){

stars.forEach(s=>s.classList.remove("hover"));

});

});

</script>

</body>
</html>