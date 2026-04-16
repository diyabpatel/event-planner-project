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

:root{
    --purple-main:#7c3aed;
    --purple-light:#a78bfa;
    --purple-soft:#ede9fe;
    --purple-bg:#f8f7ff;
    --purple-glow:rgba(124,58,237,0.25);

    --text-dark:#1e1b4b;
    --text-muted:#6d6aa3;
}

/* BODY */
body{
    font-family:'Poppins',sans-serif;
    background:
        radial-gradient(circle at 10% 20%, #f3f0ff 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, #ede9fe 0%, transparent 40%),
        #ffffff;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

/* CARD */
.card{
    width:420px;
    padding:35px;
    border-radius:24px;
    background:linear-gradient(135deg,#ffffff,#faf9ff);
    border:1px solid rgba(124,58,237,0.1);
    box-shadow:0 25px 60px rgba(124,58,237,0.15);
    text-align:center;
    position:relative;
}

/* GLOW BORDER */
.card::before{
    content:"";
    position:absolute;
    inset:0;
    border-radius:24px;
    padding:1px;
    background:linear-gradient(135deg,transparent,var(--purple-light),transparent);
    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite:xor;
            mask-composite:exclude;
    
    pointer-events:none;   /* 🔥 THIS FIX */
}

/* HEADING */
h2{
    margin-bottom:20px;
    font-size:24px;
    font-weight:700;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* LABEL */
label{
    display:block;
    margin-top:10px;
    font-size:13px;
    color:var(--text-muted);
}

/* STARS */
.stars{
    display:flex;
    justify-content:center;
    gap:12px;
    font-size:34px;
    cursor:pointer;
    margin:18px 0;
}

.star{
    color:#d1d5db;
    transition:0.2s;
}

.star:hover,
.star.hover{
    color:var(--purple-light);
    transform:scale(1.2);
}

.star.active{
    color:var(--purple-main);
    transform:scale(1.2);
}

/* TEXTAREA */
textarea{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    font-size:14px;
    resize:none;
    background:#fff;
}

textarea:focus{
    outline:none;
    border-color:var(--purple-main);
    box-shadow:0 0 0 4px var(--purple-glow);
}

/* BUTTON */
button{
    width:100%;
    padding:14px;
    margin-top:20px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    color:white;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 10px 25px rgba(124,58,237,0.25);
}

button:hover{
    transform:translateY(-3px) scale(1.03);
    box-shadow:0 18px 40px rgba(124,58,237,0.35);
}

</style>

</head>

<body>

<div class="card">

<h2>Give Feedback</h2>

<form method="POST">


<div style="font-size:12px;color:#6d6aa3;">Tap to rate your experience</div>
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
<?php include("footer.php"); ?>
</html>