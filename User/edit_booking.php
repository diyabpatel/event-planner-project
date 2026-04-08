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
$current_advance = isset($booking['advance_paid']) ? $booking['advance_paid'] : 0;
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

/* ================= CALCULATE TOTAL ================= */

$total = 0;

/* venue */

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue"));
$total += $r['price'];

/* decoration */

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$dec"));
$total += $r['price'];

/* seat */

$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat"));
$total += $r['price'] * $new_capacity;

/* food */

foreach($food as $f)
{
$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM food WHERE food_id=$f"));
$total += $r['price'] * $new_capacity;
}

/* coverage */

foreach($cover as $c)
{
$r=mysqli_fetch_assoc(mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$c"));
$total += $r['price'];
}


/* ================= NEW ADVANCE ================= */

$new_advance = round($total * 0.25, 2);


/* ================= ADVANCE DIFFERENCE ================= */

$advance_difference = $new_advance - $current_advance;


/* ================= EXTRA PAYMENT OR REFUND ================= */

if($advance_difference != 0)
{

$_SESSION['extra_payment'] = [

"booking_id"=>$booking_id,

"previous_total"=>$current_total,
"previous_advance"=>$current_advance,

"new_total"=>$total,
"new_advance"=>$new_advance,

"advance_difference"=>$advance_difference,

"capacity"=>$new_capacity,
"event_date"=>$new_date,

"venue_id"=>$venue,
"decoration_id"=>$dec,
"seat_id"=>$seat,

"food_ids"=>implode(",",$food),
"coverage_ids"=>implode(",",$cover)

];

echo "<script>window.location='payment_edit.php';</script>";
exit();

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
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#ffffff,#f3f0ff,#ede9fe);
    min-height:100vh;
    color:var(--text-dark);
}

/* CONTAINER */
.container{
    width:950px;
    max-width:95%;
    margin:50px auto;
    padding:40px;
    border-radius:24px;
    background:linear-gradient(135deg,#ffffff,#faf9ff);
    box-shadow:
        0 20px 60px rgba(124,58,237,0.15),
        0 0 0 1px rgba(167,139,250,0.15);
    position:relative;
}

/* subtle glow border */
.container::before{
    content:"";
    position:absolute;
    inset:0;
    border-radius:24px;
    padding:1px;
    background:linear-gradient(135deg,transparent,var(--purple-light),transparent);
    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;
    pointer-events:none;
}

/* HEADING */
h2{
    text-align:center;
    margin-bottom:25px;
    font-size:26px;
    font-weight:700;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* INFO BOX */
.info{
    background:linear-gradient(135deg,#f3f0ff,#ede9fe);
    padding:14px;
    border-radius:12px;
    margin-bottom:12px;
    color:var(--text-muted);
    border:1px solid rgba(167,139,250,0.2);
}

/* INPUTS */
select,input{
    width:100%;
    padding:14px;
    border-radius:14px;
    border:1px solid #e5e7eb;
    background:#ffffff;
    color:var(--text-dark);
    margin-bottom:14px;
    transition:0.3s;
}

/* focus glow */
select:focus,
input:focus{
    outline:none;
    border-color:var(--purple-main);
    box-shadow:0 0 0 4px var(--purple-glow);
}

/* CHECKBOX GROUP */
.checkbox-group{
    background:linear-gradient(135deg,#f8f7ff,#ede9fe);
    padding:18px;
    border-radius:18px;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
    margin-bottom:15px;
}

/* CHECK CARD */
.check-card{
    display:flex;
    align-items:center;
    gap:14px;
    background:#ffffff;
    padding:16px;
    border-radius:14px;
    cursor:pointer;
    border:1px solid rgba(167,139,250,0.2);
    transition:all 0.25s ease;
}

/* hover effect */
.check-card:hover{
    transform:translateY(-3px);
    border-color:var(--purple-light);
    box-shadow:0 10px 25px rgba(124,58,237,0.15);
}

/* checkbox */
.check-card input{
    width:18px;
    height:18px;
    accent-color:var(--purple-main);
}

/* BUTTON */
button{
    width:100%;
    margin-top:25px;
    padding:16px;
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,var(--purple-main),var(--purple-light));
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:all 0.3s ease;
    box-shadow:0 10px 25px rgba(124,58,237,0.25);
}

/* button hover */
button:hover{
    transform:translateY(-3px) scale(1.01);
    box-shadow:0 15px 35px rgba(124,58,237,0.35);
}

/* smooth animations */
*{
    transition:all 0.2s ease;
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