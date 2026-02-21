<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_GET['event_id'])){
    echo "Invalid Event";
    exit();
}

$event_id   = (int)$_GET['event_id'];
$package_id = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;

/* ================= EVENT ================= */
$event = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM events WHERE event_id=$event_id")
);

/* ================= PACKAGES ================= */
$packages = mysqli_query($conn,"SELECT * FROM packages WHERE event_id=$event_id");

/* ================= PACKAGE NAME → BASE ID ================= */
$base_package_id = 0;

if($package_id){
    $pkg = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT package_name FROM packages WHERE package_id=$package_id")
    );

    if($pkg){
        if($pkg['package_name']=="Basic")    $base_package_id = 1;
        if($pkg['package_name']=="Standard") $base_package_id = 2;
        if($pkg['package_name']=="Premium")  $base_package_id = 3;
    }
}

/* ================= BOOK ================= */
if(isset($_POST['book'])){

    $package_id     = (int)$_POST['package_id'];
    $venue_id       = (int)$_POST['venue_id'];
    $decoration_id  = (int)$_POST['decoration_id'];
    $seat_id        = (int)$_POST['seat_id'];
    $capacity       = (int)$_POST['capacity'];
    $event_date     = $_POST['event_date'];

    $food_ids     = isset($_POST['food_id']) ? $_POST['food_id'] : [];
    $coverage_ids = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : [];

    if($event_date < date("Y-m-d",strtotime("+5 days"))){
        echo "<script>alert('Booking allowed only after 5 days');history.back();</script>";
        exit();
    }

    $total = 0;

    $total += mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id"))['price'];
    $total += mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id"))['price'];

    $seat_price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id"))['price'];
    $total += $seat_price * $capacity;

    foreach($food_ids as $fid){
        $price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM food WHERE food_id=".(int)$fid))['price'];
        $total += $price * $capacity;
    }

    foreach($coverage_ids as $cid){
        $total += mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=".(int)$cid))['price'];
    }

    $_SESSION['booking_data'] = [
        "user_id"=>$user_id,
        "event_id"=>$event_id,
        "package_id"=>$package_id,
        "event_date"=>$event_date,
        "total_price"=>$total,
        "food_ids"=>implode(",",$food_ids),
        "coverage_ids"=>implode(",",$coverage_ids),
        "capacity"=>$capacity
    ];

    echo "<script>location='payment.php'</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Book Event</title>
<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Parisienne&display=swap');

/* ================= GLOBAL ================= */
*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:
        linear-gradient(rgba(10,25,60,0.65), rgba(10,25,60,0.7)),
        url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;
    min-height:100vh;
    color:#e5e7eb;
}

/* ================= CONTAINER ================= */
.container{
    width:950px;
    max-width:95%;
    margin:50px auto;
    padding:40px;
    border-radius:22px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(18px);
    box-shadow:0 20px 50px rgba(0,0,0,0.45);
}

/* ================= TITLE ================= */
h3{
    text-align:center;
    font-size:28px;
    font-weight:600;
    color:#dbeafe;
    margin-bottom:30px;
    letter-spacing:0.5px;
}

/* ================= LABELS ================= */
label{
    display:block;
    margin-top:18px;
    margin-bottom:6px;
    font-size:14px;
    font-weight:500;
    color:#e0e7ff;
}

/* ================= INPUTS ================= */
select,
input[type="number"],
input[type="date"]{
    width:100%;
    padding:14px;
    border-radius:14px;
    border:1px solid rgba(255,255,255,0.25);
    background:rgba(255,255,255,0.15);
    color:#f8fafc;
    font-size:14px;
    font-family:'Poppins', sans-serif;
}

select:focus,
input:focus{
    outline:none;
    border-color:#93c5fd;
    box-shadow:0 0 0 2px rgba(147,197,253,0.35);
}

select option{
    background:#1e293b;
    color:white;
}

/* ================= PACKAGE CARDS ================= */
.package-cards{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:30px;
}

.package-card{
    padding:22px;
    border-radius:18px;
    text-align:center;
    cursor:pointer;
    background:rgba(255,255,255,0.14);
    border:2px solid transparent;
    transition:0.3s ease;
}

.package-card h3{
    margin:0;
    font-size:20px;
    font-weight:500;
    color:#f1f5f9;
}

.package-card:hover{
    background:rgba(147,197,253,0.25);
    transform:translateY(-6px);
}

.package-card.active{
    border-color:#93c5fd;
    background:rgba(147,197,253,0.35);
}

/* ================= CHECKBOX GROUP ================= */
.checkbox-group{
    background:rgba(255,255,255,0.14);
    padding:18px;
    border-radius:18px;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
}

.checkbox-group label{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    border-radius:12px;
    background:rgba(255,255,255,0.12);
    cursor:pointer;
    transition:0.25s;
}

.checkbox-group label:hover{
    background:rgba(147,197,253,0.25);
}

.checkbox-group input{
    width:18px;
    height:18px;
}

/* ================= BUTTON ================= */
button{
    width:100%;
    margin-top:30px;
    padding:15px;
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,#3b82f6,#2563eb);
    color:white;
    font-size:16px;
    font-weight:500;
    font-family:'Poppins', sans-serif;
    cursor:pointer;
    transition:0.3s;
    box-shadow:0 10px 30px rgba(37,99,235,0.45);
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 40px rgba(37,99,235,0.6);
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .package-cards{
        grid-template-columns:1fr;
    }
    .checkbox-group{
        grid-template-columns:1fr;
    }
}
</style>
</head>
<body>

<?php include("../navbar.php"); ?>

<div class="container">
<h3>Book Event: <?= $event['event_name'] ?></h3>

<div class="package-cards">
<?php
while($row=mysqli_fetch_assoc($packages)){
    $active = ($package_id==$row['package_id'])?"active":"";
    echo "<div class='package-card $active'
    onclick=\"location='?event_id=$event_id&package_id={$row['package_id']}'\">
    <h3>{$row['package_name']}</h3>
    </div>";
}
?>
</div>

<?php if($base_package_id){ ?>

<form method="POST">
<input type="hidden" name="package_id" value="<?= $package_id ?>">

<label>Capacity</label>
<input type="number" name="capacity" id="capacity" min="1" required>

<label>Select Venue</label>
<select name="venue_id" required>
    <option value="">Select Seat</option>
<?php
$q=mysqli_query($conn,"SELECT * FROM venues WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q))
echo "<option value='{$r['venue_id']}' data-price='{$r['price']}'>{$r['venue_name']} ₹{$r['price']}</option>";
?>
</select>

<label>Select Decoration</label>
<select name="decoration_id" required>
    <option value="">Select Seat</option>
<?php
$q=mysqli_query($conn,"SELECT * FROM decorations WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q))
echo "<option value='{$r['decoration_id']}' data-price='{$r['price']}'>{$r['decoration_name']} ₹{$r['price']}</option>";
?>
</select>

<label>Select Seat</label>
<select name="seat_id" required>
    <option value="">Select Seat</option>
<?php
$q = mysqli_query(
    $conn,
    "SELECT * FROM seats 
     WHERE event_id = $event_id 
     AND package_id = $package_id"
);

while ($r = mysqli_fetch_assoc($q)) {
    echo "<option value='{$r['seat_id']}' data-price='{$r['price']}'>
            {$r['seat_type']} ₹{$r['price']}
          </option>";
}
?>
</select>

<label>Select Food</label>
<div class="checkbox-group">
<?php
$q=mysqli_query($conn,"SELECT * FROM food WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q))
echo "<label><input type='checkbox' name='food_id[]' value='{$r['food_id']}' data-price='{$r['price']}'> {$r['menu']} ₹{$r['price']}</label>";
?>
</div>

<label>Select Coverage</label>
<div class="checkbox-group">
<?php
$q=mysqli_query($conn,"SELECT * FROM coverage WHERE event_id=$event_id AND package_id=$package_id");
while($r=mysqli_fetch_assoc($q))
echo "<label><input type='checkbox' name='coverage_id[]' value='{$r['coverage_id']}' data-price='{$r['price']}'> {$r['coverage_type']} ₹{$r['price']}</label>";
?>
</div>

<label>Date</label>
<input type="date" name="event_date" min="<?= date('Y-m-d',strtotime('+5 days')) ?>" required>

<button type="submit" name="book">Book Now</button>
</form>

<?php } ?>
</div>

<script>
function calculateTotal(){
    var total=0;
    var capacity=document.getElementById("capacity").value||0;

    document.querySelectorAll("select option:checked").forEach(o=>{
        if(o.dataset.price) total+=parseInt(o.dataset.price);
    });

    document.querySelectorAll("input[type=checkbox]:checked").forEach(cb=>{
        total+=parseInt(cb.dataset.price)*(cb.name=="food_id[]" ? capacity:1);
    });

    document.getElementById("totalAmount")?.innerText=total;
}
document.addEventListener("change",calculateTotal);
</script>

</body>
</html>