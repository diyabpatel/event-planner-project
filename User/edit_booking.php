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

// PACKAGE NAME
$p = mysqli_fetch_assoc(mysqli_query($conn,"SELECT package_name FROM packages WHERE package_id=".$booking['package_id']));
$package_name = strtolower($p['package_name']);

// MAX CAPACITY
if($package_name == "basic"){
    $max_capacity = 200;
}elseif($package_name == "standard"){
    $max_capacity = 400;
}else{
    $max_capacity = 600;
}

$min_date = date('Y-m-d', strtotime('+2 days'));
$max_date = date('Y-m-d', strtotime('+30 days'));

$venues = mysqli_query($conn,"SELECT * FROM venues WHERE event_id=$event_id AND package_id=$package_id");
$decor  = mysqli_query($conn,"SELECT * FROM decorations WHERE event_id=$event_id AND package_id=$package_id");
$seats  = mysqli_query($conn,"SELECT * FROM seats WHERE event_id=$event_id AND package_id=$package_id");
$foods  = mysqli_query($conn,"SELECT * FROM food WHERE event_id=$event_id AND package_id=$package_id");
$coverage = mysqli_query($conn,"SELECT * FROM coverage WHERE event_id=$event_id AND package_id=$package_id");

if(isset($_POST['update'])){

$capacity = $_POST['capacity'];
$date     = $_POST['event_date'];

// CAPACITY VALIDATION
if($capacity > $max_capacity){
    echo "<script>alert('Max capacity for $package_name is $max_capacity');</script>";
    exit();
}

// DATE VALIDATION
if($date < $min_date || $date > $max_date){
    echo "<script>alert('Date must be between 2 to 30 days from today');</script>";
    exit();
}

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

// calculate NEW total same logic
// 🔥 RE-CALCULATE AFTER UPDATE

// venue
$v = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue"));
$venue_price = isset($v['price']) ? $v['price'] : 0;

$d = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$dec"));
$dec_price = isset($d['price']) ? $d['price'] : 0;

$s = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat"));
$seat_price = isset($s['price']) ? $s['price'] : 0;
$seat_price = $seat_price * $capacity;

// food
$food_total = 0;
if(!empty($food)){
    $ids = implode(",", $food);
    $res = mysqli_query($conn,"SELECT price FROM food WHERE food_id IN($ids)");
    while($f=mysqli_fetch_assoc($res)){
        $food_total += $f['price'] * $capacity;
    }
}

// coverage
$cov_total = 0;
if(!empty($cover)){
    $ids = implode(",", $cover);
    $res = mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id IN($ids)");
    while($c=mysqli_fetch_assoc($res)){
        $cov_total += $c['price'];
    }
}

// total
$new_total = $venue_price + $dec_price + $seat_price + $food_total + $cov_total;

// advance SAME rahega
$advance = isset($booking['advance_paid']) ? $booking['advance_paid'] : 0;

// remaining
$new_remaining = $new_total - $advance;
echo "
<style>
.popup-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    backdrop-filter:blur(6px);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999;
    animation:fadeIn 0.3s ease;
}

.popup-box{
    background:linear-gradient(135deg,#ffffff,#f3f0ff);
    padding:30px;
    border-radius:20px;
    width:350px;
    text-align:center;
    box-shadow:0 25px 60px rgba(124,58,237,0.3);
    animation:scaleIn 0.3s ease;
}

.popup-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:15px;
    color:#4c1d95;
}

.popup-info{
    margin:8px 0;
    font-size:15px;
    color:#374151;
}

.popup-info b{
    color:#111827;
}

.popup-btn{
    margin-top:20px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#7c3aed,#a78bfa);
    color:white;
    font-size:14px;
    cursor:pointer;
    transition:0.3s;
}

.popup-btn:hover{
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(124,58,237,0.4);
}

/* animations */
@keyframes fadeIn{
    from{opacity:0;}
    to{opacity:1;}
}

@keyframes scaleIn{
    from{transform:scale(0.8);opacity:0;}
    to{transform:scale(1);opacity:1;}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){

let popup = document.createElement('div');
popup.classList.add('popup-overlay');

popup.innerHTML = `
<div class='popup-box'>

<div class='popup-title'>Booking Updated</div>

<div class='popup-info'><b>Updated Total Price:</b> &#8377; ".number_format($new_total,2)."</div>
<div class='popup-info'><b>Earlier Paid:</b> &#8377; ".number_format($advance,2)."</div>
<div class='popup-info'><b>Remaining Amount:</b> &#8377; ".number_format($new_remaining,2)."</div>

<button class='popup-btn' onclick='window.location=\"my_bookings.php\"'>OK</button>

</div>
`;

document.body.appendChild(popup);

});
</script>
";
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
.price-summary{
    display:flex;
    justify-content:space-between;
    gap:20px;
    margin-bottom:25px;
}

.price-summary div{
    flex:1;
    background:#f3f0ff;
    padding:15px;
    border-radius:12px;
    text-align:center;
}

.price-summary span{
    font-size:12px;
    color:#6b6b8a;
}

.price-summary b{
    display:block;
    margin-top:5px;
    font-size:18px;
}
#updateBtn:disabled{
    background:#9ca3af;
    cursor:not-allowed;
}
</style>
</head>

<body>

<div class="container">
<?php
/* CURRENT PRICE CALCULATION */

// venue price
$venue_price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=".$booking['venue_id']))['price'];

// decoration price
$dec_price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=".$booking['decoration_id']))['price'];

// seat price
$seat_data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=".$booking['seat_id']));
$seat_price = $seat_data['price'] * $booking['capacity'];

// food price
$food_total = 0;
if(!empty($booking['food_ids'])){
    $food_res = mysqli_query($conn,"SELECT price FROM food WHERE food_id IN(".$booking['food_ids'].")");
    while($f=mysqli_fetch_assoc($food_res)){
        $food_total += $f['price'] * $booking['capacity'];
    }
}

// coverage price
$cov_total = 0;
if(!empty($booking['coverage_ids'])){
    $cov_res = mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id IN(".$booking['coverage_ids'].")");
    while($c=mysqli_fetch_assoc($cov_res)){
        $cov_total += $c['price'];
    }
}

// TOTAL
$current_total = $venue_price + $dec_price + $seat_price + $food_total + $cov_total;

// advance
$advance = $booking['advance_paid'];
$remaining = $current_total - $advance;
?>
<h2>Edit <?= $event['event_name'] ?> Booking</h2>
<div class="price-summary">

<div>
<span>Total Price</span>
<b>₹ <?= number_format($current_total,2) ?></b>
</div>

<div>
<span>Advance Paid</span>
<b>₹ <?= number_format($advance,2) ?></b>
</div>

<div>
<span>Remaining</span>
<b>₹ <?= number_format($remaining,2) ?></b>
</div>

</div>


<form method="POST">

<input type="hidden" name="venue_id" id="venueInput">
<input type="hidden" name="decoration_id" id="decorationInput">
<input type="hidden" name="seat_id" id="seatInput">

<!-- ✅ RESTORED -->
<div class="input">
<label>Capacity</label>
<input type="number"
       name="capacity"
       value="<?= $booking['capacity'] ?>"
       min="1"
       max="<?= $max_capacity ?>"
       required>
       <div id="capacityError" style="color:red;font-size:12px;margin-top:5px;"></div>
</div>

<div class="input">
<label>Date</label>
<input type="date"
       name="event_date"
       value="<?= $booking['event_date'] ?>"
       min="<?= $min_date ?>"
       max="<?= $max_date ?>"
       required>
    <div id="dateError" style="color:red;font-size:12px;margin-top:5px;"></div>
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


<button name="update" id="updateBtn">Update Booking</button>

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
/* ================= LIVE VALIDATION ================= */

const capacityInput = document.querySelector('input[name="capacity"]');
const dateInput = document.querySelector('input[name="event_date"]');

const capacityError = document.getElementById("capacityError");
const dateError = document.getElementById("dateError");
const updateBtn = document.getElementById("updateBtn");

// PHP values pass to JS
const maxCapacity = <?= $max_capacity ?>;
const minDate = "<?= $min_date ?>";
const maxDate = "<?= $max_date ?>";

/* CAPACITY VALIDATION */
capacityInput.addEventListener("input", function(){

    if(this.value > maxCapacity){
        capacityError.innerText = "Max allowed capacity is " + maxCapacity;
        updateBtn.disabled = true;
    }else{
        capacityError.innerText = "";
        updateBtn.disabled = false;
    }

});

/* DATE VALIDATION */
dateInput.addEventListener("input", function(){

    if(this.value < minDate || this.value > maxDate){
        dateError.innerText = "Select date between allowed range";
        updateBtn.disabled = true;
    }else{
        dateError.innerText = "";
        updateBtn.disabled = false;
    }

});
</script>