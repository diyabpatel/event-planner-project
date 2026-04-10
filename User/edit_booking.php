<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM bookings WHERE booking_id=$booking_id AND user_id=$user_id");
if(mysqli_num_rows($q)==0){ echo "Invalid booking"; exit(); }

$booking = mysqli_fetch_assoc($q);

$current_food = explode(",", $booking['food_ids']);
$current_coverage = explode(",", $booking['coverage_ids']);

$event_id = $booking['event_id'];
$package_id = $booking['package_id'];

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE event_id=$event_id"));

$venues = mysqli_query($conn,"SELECT * FROM venues WHERE event_id=$event_id AND package_id=$package_id");
$decor  = mysqli_query($conn,"SELECT * FROM decorations WHERE event_id=$event_id AND package_id=$package_id");
$seats  = mysqli_query($conn,"SELECT * FROM seats WHERE event_id=$event_id AND package_id=$package_id");
$foods  = mysqli_query($conn,"SELECT * FROM food WHERE event_id=$event_id AND package_id=$package_id");
$coverage = mysqli_query($conn,"SELECT * FROM coverage WHERE event_id=$event_id AND package_id=$package_id");

if(isset($_POST['update'])){

$venue = $_POST['venue_id'];
$dec   = $_POST['decoration_id'];
$seat  = $_POST['seat_id'];
$food  = isset($_POST['food_id']) ? $_POST['food_id'] : [];
$cover = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : [];

$capacity = $_POST['capacity'];
$date     = $_POST['event_date'];

mysqli_query($conn,"UPDATE bookings SET
venue_id='$venue',
decoration_id='$dec',
seat_id='$seat',
food_ids='".implode(",",$food)."',
coverage_ids='".implode(",",$cover)."',
capacity='$capacity',
event_date='$date'
WHERE booking_id=$booking_id");

echo "<script>alert('Updated Successfully');window.location='my_bookings.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Booking</title>

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

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    transition:all 0.25s ease;
}

/* BODY */
body{
    font-family:'Poppins',sans-serif;
    background:
        radial-gradient(circle at 10% 20%, #f3f0ff 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, #ede9fe 0%, transparent 40%),
        #ffffff;
    color:var(--text-dark);
}

/* CONTAINER */
.container{
    max-width:1150px;
    margin:auto;
    padding:50px 25px;
}

/* HEADING */
h2{
    text-align:center;
    font-size:34px;
    margin-bottom:35px;
    font-weight:700;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* FORM */
form{
    margin-top:45px;
    padding:35px;
    border-radius:24px;
    background:linear-gradient(135deg,#ffffff,#faf9ff);
    border:1px solid rgba(124,58,237,0.1);
    box-shadow:0 25px 60px rgba(124,58,237,0.12);
    position:relative;
}

/* glow border */
form::before{
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
    pointer-events:none;
}

/* INPUT */
.input{
    margin-bottom:20px;
}

.input label{
    font-size:13px;
    color:var(--text-muted);
    font-weight:500;
}

/* INPUT FIELD */
.input input{
    width:100%;
    padding:13px;
    margin-top:6px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    background:#fff;
    font-size:14px;
}

.input input:focus{
    outline:none;
    border-color:var(--purple-main);
    box-shadow:0 0 0 4px var(--purple-glow);
}

/* GRID */
.card-grid,
.venue-grid,
.seat-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-top:12px;
}

.food-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
}

/* CARDS */
.venue-card,
.seat-card,
.select-card,
.food-card,
.decor-card,
.coverage-card{
    background:linear-gradient(135deg,#ffffff,#faf9ff);
    border:1px solid #eee;
    border-radius:18px;
    padding:16px;
    cursor:pointer;
    position:relative;
    box-shadow:0 6px 15px rgba(124,58,237,0.08);
}

/* IMAGE */
.venue-card img,
.food-card img,
.seat-card img{
    width:100%;
    aspect-ratio:1/1;
    object-fit:cover;
    border-radius:12px;
    margin-bottom:10px;
}

/* HOVER */
.venue-card:hover,
.seat-card:hover,
.select-card:hover,
.food-card:hover,
.decor-card:hover,
.coverage-card:hover{
    transform:translateY(-6px) scale(1.03);
    box-shadow:0 20px 35px rgba(124,58,237,0.15);
    border-color:var(--purple-light);
}

/* ACTIVE */
.venue-card.active,
.seat-card.active,
.select-card.active,
.food-card.active,
.decor-card.active,
.coverage-card.active{
    border:2px solid var(--purple-main);
    box-shadow:0 10px 30px rgba(124,58,237,0.25);
}

/* CHECK ICON */
.venue-card.active::after,
.seat-card.active::after,
.select-card.active::after,
.food-card.active::after,
.decor-card.active::after,
.coverage-card.active::after{
    content:"✔";
    position:absolute;
    top:10px;
    right:10px;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    color:white;
    padding:5px 8px;
    border-radius:10px;
    font-size:12px;
}

/* BUTTON */
button{
    width:100%;
    padding:15px;
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

/* RESPONSIVE */
@media(max-width:992px){
    .card-grid,
    .venue-grid,
    .seat-grid{
        grid-template-columns:repeat(2,1fr);
    }
    .food-grid{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:600px){
    .card-grid,
    .venue-grid,
    .seat-grid,
    .food-grid{
        grid-template-columns:1fr;
    }
    .container{
        padding:30px 15px;
    }
}
</style>
</head>

<body>

<div class="container">
<h2>Edit <?= $event['event_name'] ?> Booking</h2>

<form method="POST">

<input type="hidden" name="venue_id" id="venueInput">
<input type="hidden" name="decoration_id" id="decorationInput">
<input type="hidden" name="seat_id" id="seatInput">

<!-- ✅ RESTORED -->
<div class="input">
<label>Capacity</label>
<input type="number" name="capacity" value="<?= $booking['capacity'] ?>" required>
</div>

<div class="input">
<label>Date</label>
<input type="date" name="event_date" value="<?= $booking['event_date'] ?>" required>
</div>
<!-- ================= VENUE ================= -->
<div class="input">
<label>Select Venue</label>

<div class="venue-grid">
<?php
while($v=mysqli_fetch_assoc($venues)){

$folder = strtolower(str_replace(" ", "_", $event['event_name']));
$img = "../uploads/images/venues/".$folder."/".$v['venue_image'];

$active = ((int)$booking['venue_id'] === (int)$v['venue_id']) ? "active" : "";

echo "
<div class='venue-card $active'
     data-id='{$v['venue_id']}'
     onclick='selectVenue(this)'>

<img src='$img'>

<h4>{$v['venue_name']}</h4>
<p>₹{$v['price']}</p>

</div>
";
}
?>
</div>
</div>


<!-- ================= DECORATION ================= -->
<div class="input">
<label>Decoration</label>

<div class="card-grid">
<?php
while($d=mysqli_fetch_assoc($decor)){

$active = ((int)$booking['decoration_id'] === (int)$d['decoration_id']) ? "active" : "";

echo "
<div class='decor-card $active'
     data-id='{$d['decoration_id']}'
     onclick='selectDecoration(this)'>

<h4>{$d['decoration_name']}</h4>
<p>₹{$d['price']}</p>

</div>
";
}
?>
</div>
</div>


<!-- ================= SEATS ================= -->
<div class="input">
<label>Seats</label>

<div class="seat-grid">
<?php
while($s=mysqli_fetch_assoc($seats)){

$img = "../uploads/images/seats/".$s['seat_images'];

$active = ((int)$booking['seat_id'] === (int)$s['seat_id']) ? "active" : "";

echo "
<div class='seat-card $active'
     data-id='{$s['seat_id']}'
     onclick='selectSeat(this)'>

<img src='$img'>

<h4>{$s['seat_type']}</h4>
<p>₹{$s['price']} per seat</p>

</div>
";
}
?>
</div>
</div>


<!-- ================= FOOD ================= -->
<div class="input">
<label>Food</label>

<div class="food-grid">
<?php
while($f=mysqli_fetch_assoc($foods)){

$img = "../uploads/images/food/".$f['food_image'];

$active = in_array($f['food_id'],$current_food) ? "active" : "";

echo "
<div class='food-card $active'
     data-id='{$f['food_id']}'
     onclick='toggleFood(this)'>

<img src='$img'>

<h4>{$f['menu']}</h4>
<p>₹{$f['price']} / person</p>

</div>
";
}
?>
</div>

<div id="foodInputs"></div>
</div>


<!-- ================= COVERAGE ================= -->
<div class="input">
<label>Coverage</label>

<div class="card-grid">
<?php
while($c=mysqli_fetch_assoc($coverage)){

$active = in_array($c['coverage_id'],$current_coverage) ? "active" : "";

echo "
<div class='coverage-card $active'
     data-id='{$c['coverage_id']}'
     onclick='toggleCoverage(this)'>

<h4>{$c['coverage_type']}</h4>
<p>₹{$c['price']}</p>

</div>
";
}
?>
</div>

<div id="coverageInputs"></div>
</div>


<button name="update">Update Booking</button>

</form>
</div>
<script>

/* ================= INIT (VERY IMPORTANT) ================= */
window.addEventListener("load", function(){

// VENUE
let v = document.querySelector(".venue-card.active");
if(v){
    document.getElementById("venueInput").value = v.dataset.id;
}

// SEAT
let s = document.querySelector(".seat-card.active");
if(s){
    document.getElementById("seatInput").value = s.dataset.id;
}

// DECORATION
let d = document.querySelector(".decor-card.active");
if(d){
    document.getElementById("decorationInput").value = d.dataset.id;
}

// FOOD + COVERAGE INIT
renderFoods();
renderCoverage();

});


/* ================= CLICK FUNCTIONS ================= */

function selectVenue(el){
document.querySelectorAll(".venue-card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");
document.getElementById("venueInput").value = el.dataset.id;
}

function selectSeat(el){
document.querySelectorAll(".seat-card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");
document.getElementById("seatInput").value = el.dataset.id;
}

function selectDecoration(el){
document.querySelectorAll(".decor-card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");
document.getElementById("decorationInput").value = el.dataset.id;
}


/* ================= FOOD ================= */

let selectedFoods = <?= json_encode($current_food) ?>;

function toggleFood(el){

let id = el.dataset.id;

if(selectedFoods.includes(id)){
    selectedFoods = selectedFoods.filter(f=>f!=id);
    el.classList.remove("active");
}else{
    selectedFoods.push(id);
    el.classList.add("active");
}

renderFoods();
}

function renderFoods(){
let html="";
selectedFoods.forEach(f=>{
html += `<input type="hidden" name="food_id[]" value="${f}">`;
});
document.getElementById("foodInputs").innerHTML = html;
}


/* ================= COVERAGE ================= */

let selectedCoverage = <?= json_encode($current_coverage) ?>;

function toggleCoverage(el){

let id = el.dataset.id;

if(selectedCoverage.includes(id)){
    selectedCoverage = selectedCoverage.filter(c=>c!=id);
    el.classList.remove("active");
}else{
    selectedCoverage.push(id);
    el.classList.add("active");
}

renderCoverage();
}

function renderCoverage(){
let html="";
selectedCoverage.forEach(c=>{
html += `<input type="hidden" name="coverage_id[]" value="${c}">`;
});
document.getElementById("coverageInputs").innerHTML = html;
}

</script>