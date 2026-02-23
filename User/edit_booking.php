<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id']);

/* ================= FETCH BOOKING ================= */

$q = mysqli_query($conn,"
SELECT * FROM bookings
WHERE booking_id=$booking_id
AND user_id=$user_id
");

if(mysqli_num_rows($q)==0){
    echo "Invalid booking";
    exit();
}

$booking = mysqli_fetch_assoc($q);

$current_total   = $booking['total_price'];
$current_advance = $booking['advance_paid'];
$current_date    = $booking['event_date'];

$event_id   = $booking['event_id'];
$package_id = $booking['package_id'];

$current_food     = explode(",",$booking['food_ids']);
$current_coverage = explode(",",$booking['coverage_ids']);


/* ================= FETCH OPTIONS ================= */

$venues   = mysqli_query($conn,"SELECT * FROM venues WHERE package_id=$package_id");
$decor    = mysqli_query($conn,"SELECT * FROM decorations WHERE package_id=$package_id");
$seats    = mysqli_query($conn,"SELECT * FROM seats WHERE package_id=$package_id");
$foods    = mysqli_query($conn,"SELECT * FROM food WHERE package_id=$package_id");
$coverage = mysqli_query($conn,"SELECT * FROM coverage WHERE package_id=$package_id");


/* ================= UPDATE ================= */

if(isset($_POST['update']))
{

$new_capacity = intval($_POST['capacity']);
$new_date     = $_POST['event_date'];

$venue = intval($_POST['venue_id']);
$dec   = intval($_POST['decoration_id']);
$seat  = intval($_POST['seat_id']);

$food  = isset($_POST['food_id']) ? $_POST['food_id'] : [];
$cover = isset($_POST['coverage_id']) ? $_POST['coverage_id'] : [];


/* DATE VALIDATION */

if($new_date < $current_date)
{
echo "<script>alert('Date cannot be earlier than current event date');</script>";
}
else
{

/* CALCULATE TOTAL */

$total = 0;

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue"));
$total += $r['price'];

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$dec"));
$total += $r['price'];

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat"));
$total += $r['price'] * $new_capacity;

foreach($food as $f)
{
$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM food WHERE food_id=$f"));
$total += $r['price'] * $new_capacity;
}

foreach($cover as $c)
{
$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$c"));
$total += $r['price'];
}


/* NEW ADVANCE */

$new_advance = round($total * 0.25, 2);


/* ================= EXTRA PAYMENT ================= */

if($new_advance > $current_advance)
{

$extra = $new_advance - $current_advance;


/* SAVE DATA FOR PAYMENT PAGE */

$_SESSION['extra_payment'] = [

"booking_id"=>$booking_id,

"new_total"=>$total,

"extra_advance"=>$extra,

"new_advance"=>$new_advance,

"remaining_after"=>$total - $new_advance,

"capacity"=>$new_capacity,

"event_date"=>$new_date,

"venue_id"=>$venue,

"decoration_id"=>$dec,

"seat_id"=>$seat,

"food_ids"=>implode(",",$food),

"coverage_ids"=>implode(",",$cover)

];


echo "<script>
alert('Extra advance payment required: ₹$extra');
window.location='payment.php';
</script>";

exit();

}


/* ================= REFUND ================= */

else if($new_advance < $current_advance)
{

$refund = $current_advance - $new_advance;

echo "<script>alert('Refund amount: ₹$refund');</script>";

}


/* ================= UPDATE DIRECTLY ================= */

$remaining = $total - $new_advance;

mysqli_query($conn,"
UPDATE bookings SET

capacity='$new_capacity',
event_date='$new_date',

venue_id='$venue',
decoration_id='$dec',
seat_id='$seat',

total_price='$total',
advance_paid='$new_advance',
remaining_amount='$remaining',

food_ids='".implode(",",$food)."',
coverage_ids='".implode(",",$cover)."'

WHERE booking_id=$booking_id
");


echo "<script>
alert('Booking Updated Successfully');
window.location='my_bookings.php';
</script>";

exit();

}

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Edit Booking</title>

<style>

body{
margin:0;
font-family:Segoe UI;
background:
linear-gradient(rgba(10,25,60,0.65), rgba(10,25,60,0.7)),
url('../uploads/images/annual/stage_bg.jpg') center/cover no-repeat;
min-height:100vh;
color:white;
}

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

h2{
text-align:center;
margin-bottom:20px;
}

.info{
background:rgba(0,0,0,0.3);
padding:12px;
border-radius:10px;
margin-bottom:10px;
}


select,input{
width:100%;
padding:14px;
border-radius:14px;
border:1px solid rgba(255,255,255,0.3);
background:#0f172a;
color:white;
}

select option{
background:#0f172a;
color:white;
}


.checkbox-group{
background:rgba(15,23,42,0.6);
padding:18px;
border-radius:18px;
display:grid;
grid-template-columns:repeat(2,1fr);
gap:16px;
}

.check-card{
display:flex;
align-items:center;
gap:14px;
background:rgba(255,255,255,0.08);
padding:16px;
border-radius:14px;
cursor:pointer;
}

.check-card input{
width:18px;
height:18px;
accent-color:#3b82f6;
}

button{
width:100%;
margin-top:25px;
padding:15px;
border:none;
border-radius:18px;
background:linear-gradient(135deg,#3b82f6,#2563eb);
color:white;
font-size:16px;
cursor:pointer;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Booking</h2>

<div class="info">Current Total: ₹<?php echo number_format($current_total,2); ?></div>

<div class="info">Advance Paid: ₹<?php echo number_format($current_advance,2); ?></div>

<form method="POST">


Capacity
<input type="number" name="capacity" value="<?php echo $booking['capacity']; ?>" required>


Event Date
<input type="date"
name="event_date"
value="<?php echo $booking['event_date']; ?>"
min="<?php echo $booking['event_date']; ?>"
required>


Venue
<select name="venue_id" required>

<?php while($v=mysqli_fetch_assoc($venues)){ ?>

<option value="<?php echo $v['venue_id']; ?>"
<?php if(isset($booking['venue_id']) && $booking['venue_id']==$v['venue_id']) echo "selected"; ?>>

<?php echo $v['venue_name']; ?>

</option>

<?php } ?>

</select>


Decoration
<select name="decoration_id" required>

<?php while($d=mysqli_fetch_assoc($decor)){ ?>

<option value="<?php echo $d['decoration_id']; ?>"
<?php if(isset($booking['decoration_id']) && $booking['decoration_id']==$d['decoration_id']) echo "selected"; ?>>

<?php echo $d['decoration_name']; ?>

</option>

<?php } ?>

</select>


Seat
<select name="seat_id" required>

<?php while($s=mysqli_fetch_assoc($seats)){ ?>

<option value="<?php echo $s['seat_id']; ?>"
<?php if(isset($booking['seat_id']) && $booking['seat_id']==$s['seat_id']) echo "selected"; ?>>

<?php echo $s['seat_type']; ?>

</option>

<?php } ?>

</select>


Food

<div class="checkbox-group">

<?php while($f=mysqli_fetch_assoc($foods)){ ?>

<label class="check-card">

<input type="checkbox"
name="food_id[]"
value="<?php echo $f['food_id']; ?>"
<?php if(in_array($f['food_id'],$current_food)) echo "checked"; ?>

>

<?php echo $f['menu']; ?>

</label>

<?php } ?>

</div>


Coverage

<div class="checkbox-group">

<?php while($c=mysqli_fetch_assoc($coverage)){ ?>

<label class="check-card">

<input type="checkbox"
name="coverage_id[]"
value="<?php echo $c['coverage_id']; ?>"
<?php if(in_array($c['coverage_id'],$current_coverage)) echo "checked"; ?>

>

<?php echo $c['coverage_type']; ?>

</label>

<?php } ?>

</div>


<button name="update">Update Booking</button>

</form>

</div>

</body>
</html>