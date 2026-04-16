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
}

/* TITLE */

.title{
text-align:center;
font-size:42px;
margin-bottom:50px;
color:black;
letter-spacing:2px;
font-weight:600;
padding:20px;
}

/* GALLERY */

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
cursor:pointer;
}

.card:hover{
transform:translateY(-8px) scale(1.02);
box-shadow:0 20px 40px rgba(0,0,0,0.25);
border:2px solid #ffb6e6;
}

/* IMAGE GRID */

.open-gallery{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:6px;
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

/* TITLE */

.card h3{
grid-column:1/4;
text-align:center;
padding:12px;
font-size:20px;
color:#444;
font-weight:500;
}

/* LIGHTBOX */

#lightbox{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.9);
display:none;
align-items:center;
justify-content:center;
z-index:9999;
flex-direction:column;
}

#lightbox img{
width:100%;
height:100%;
object-fit:contain;
border-radius:12px;
box-shadow:0 0 40px rgba(255,255,255,0.3);
}

#closeBtn{
position:absolute;
top:20px;
right:40px;
font-size:40px;
color:white;
cursor:pointer;
}

.nav{
position:absolute;
top:50%;
transform:translateY(-50%);
font-size:50px;
color:white;
cursor:pointer;
padding:10px;
}

.prev{ left:30px; }
.next{ right:30px; }

</style>
</head>

<body>

<?php include("/navbar.php"); ?>

<h1 class="title">College Event Gallery</h1>

<div class="gallery">

<!-- ANNUAL DAY -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/annual/annualday1.png",
"uploads/images/annual/annualday2.png",
"uploads/images/annual/annualday3.png",
"uploads/images/annual/annualday4.png",
"uploads/images/annual/annualday5.png",
"uploads/images/annual/annualday6.png"
]'>
<img src="uploads/images/annual/annualday1.png">
<img src="uploads/images/annual/annualday2.png">
<img src="uploads/images/annual/annualday3.png">
<img src="uploads/images/annual/annualday4.png">
<img src="uploads/images/annual/annualday5.png">
<img src="uploads/images/annual/annualday6.png">
<h3>Annual Day</h3>
</div>
</div>

<!-- CONVOCATION -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/convocation/convocation1.png",
"uploads/images/convocation/convocation2.png",
"uploads/images/convocation/convocation3.png",
"uploads/images/convocation/convocation4.png",
"uploads/images/convocation/convocation5.png",
"uploads/images/convocation/convocation6.png"
]'>
<img src="uploads/images/convocation/convocation1.png">
<img src="uploads/images/convocation/convocation2.png">
<img src="uploads/images/convocation/convocation3.png">
<img src="uploads/images/convocation/convocation4.png">
<img src="uploads/images/convocation/convocation5.png">
<img src="uploads/images/convocation/convocation6.png">
<h3>Convocation</h3>
</div>
</div>

<!-- FAREWELL -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/farewell/farewell1.png",
"uploads/images/farewell/farewell2.png",
"uploads/images/farewell/farewell3.png",
"uploads/images/farewell/farewell4.png",
"uploads/images/farewell/farewell5.png",
"uploads/images/farewell/farewell6.png"
]'>
<img src="uploads/images/farewell/farewell1.png">
<img src="uploads/images/farewell/farewell2.png">
<img src="uploads/images/farewell/farewell3.png">
<img src="uploads/images/farewell/farewell4.png">
<img src="uploads/images/farewell/farewell5.png">
<img src="uploads/images/farewell/farewell6.png">
<h3>Farewell Party</h3>
</div>
</div>

<!-- FRESHERS -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/freshers/fresher1.png",
"uploads/images/freshers/fresher2.png",
"uploads/images/freshers/fresher3.png",
"uploads/images/freshers/fresher4.png",
"uploads/images/freshers/fresher5.png",
"uploads/images/freshers/fresher6.png"
]'>
<img src="uploads/images/freshers/fresher1.png">
<img src="uploads/images/freshers/fresher2.png">
<img src="uploads/images/freshers/fresher3.png">
<img src="uploads/images/freshers/fresher4.png">
<img src="uploads/images/freshers/fresher5.png">
<img src="uploads/images/freshers/fresher6.png">
<h3>Freshers Party</h3>
</div>
</div>

<!-- SEMINAR -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/seminar/seminar1.png",
"uploads/images/seminar/seminar2.png",
"uploads/images/seminar/seminar3.png",
"uploads/images/seminar/seminar4.png",
"uploads/images/seminar/seminar5.png",
"uploads/images/seminar/seminar6.png"
]'>
<img src="uploads/images/seminar/seminar1.png">
<img src="uploads/images/seminar/seminar2.png">
<img src="uploads/images/seminar/seminar3.png">
<img src="uploads/images/seminar/seminar4.png">
<img src="uploads/images/seminar/seminar5.png">
<img src="uploads/images/seminar/seminar6.png">
<h3>Seminar</h3>
</div>
</div>

<!-- SPORTS DAY -->
<div class="card">
<div class="open-gallery" data-images='[
"uploads/images/sports day/sports_day1.png",
"uploads/images/sports day/sports_day2.png",
"uploads/images/sports day/sports_day3.png",
"uploads/images/sports day/sports_day4.png",
"uploads/images/sports day/sports_day5.png",
"uploads/images/sports day/sports_day6.png"
]'>
<img src="uploads/images/sports day/sports_day1.png">
<img src="uploads/images/sports day/sports_day2.png">
<img src="uploads/images/sports day/sports_day3.png">
<img src="uploads/images/sports day/sports_day4.png">
<img src="uploads/images/sports day/sports_day5.png">
<img src="uploads/images/sports day/sports_day6.png">
<h3>Sports Day</h3>
</div>
</div>

</div>

<!-- LIGHTBOX -->
<div id="lightbox">
<span id="closeBtn">&times;</span>
<img id="lightboxImg">
<div class="nav prev">&#10094;</div>
<div class="nav next">&#10095;</div>
</div>

<script>

let currentIndex = 0;
let images = [];

const lightbox = document.getElementById("lightbox");
const lightboxImg = document.getElementById("lightboxImg");

// OPEN
document.querySelectorAll(".open-gallery").forEach(card=>{
card.addEventListener("click", function(){
images = JSON.parse(this.getAttribute("data-images"));
currentIndex = 0;
showImage();
lightbox.style.display = "flex";
});
});

function showImage(){
lightboxImg.src = images[currentIndex];
}

// NEXT
document.querySelector(".next").onclick = ()=>{
currentIndex = (currentIndex + 1) % images.length;
showImage();
};

// PREV
document.querySelector(".prev").onclick = ()=>{
currentIndex = (currentIndex - 1 + images.length) % images.length;
showImage();
};

// CLOSE
document.getElementById("closeBtn").onclick = ()=>{
lightbox.style.display = "none";
};

</script>

</body>
<?php include("footer.php"); ?>
</html>