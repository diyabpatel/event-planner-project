<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

include("../db.php");

date_default_timezone_set('Asia/Kolkata');

$user_id = $_SESSION['user_id'];

/* FILTER */
$filter = isset($_GET['status']) ? $_GET['status'] : 'approved';

/* FILTER LOGIC */
if($filter == 'pending'){
    $status_condition = "AND b.payment_status = 'Pending'";
}else{
    $status_condition = "AND (b.payment_status = 'Approved' OR b.payment_status = 'Full Payment Done')";
}

/* ✅ FINAL QUERY (FIXED TABLE NAMES) */
$query = "
SELECT b.*, 
e.event_name, e.image, 
p.package_name,

v.venue_name, v.venue_image,
s.seat_type, s.seat_images,
d.decoration_name

FROM bookings b

LEFT JOIN events e ON b.event_id = e.event_id
LEFT JOIN packages p ON b.package_id = p.package_id

LEFT JOIN venues v ON b.venue_id = v.venue_id
LEFT JOIN seats s ON b.seat_id = s.seat_id
LEFT JOIN decorations d ON b.decoration_id = d.decoration_id

WHERE b.user_id = $user_id
$status_condition
ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn,$query);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Bookings</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', system-ui;
    background:#f4f5f9;
}

.container{
    max-width:1100px;
    margin:50px auto;
    padding:10px;
}

h2{
    text-align:center;
    margin-bottom:30px;
}

/* FILTER BUTTON */
.filter-btn{
    padding:8px 18px;
    margin:5px;
    border-radius:20px;
    text-decoration:none;
    background:#e5e7eb;
    color:#333;
    transition:0.3s;
}
.filter-btn.active{
    background:#7c3aed;
    color:white;
}

/* CARD */
.card{
    display:flex;
    align-items:center;
    background:#fff;
    border-radius:14px;
    margin-bottom:20px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    cursor:pointer;
    transition:0.3s;
}
.card:hover{
    transform:scale(1.02);
}

.card-img{
    width:200px;
    height:140px;
}
.card-img img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.card-content{
    padding:15px;
}
.event-name{
    font-size:18px;
    font-weight:600;
}
.event-date{
    font-size:13px;
    color:#6b7280;
    margin-top:5px;
}

/* MODAL BACKGROUND */
.modal{
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
}

/* MODAL BOX */
.modal-box{
    background:#fff;
    width:500px;
    max-width:90%;
    max-height:85vh;
    overflow-y:auto;
    margin:60px auto;
    padding:20px;
    border-radius:16px;
    position:relative;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
}

/* SCROLLBAR */
.modal-box::-webkit-scrollbar{
    width:6px;
}
.modal-box::-webkit-scrollbar-thumb{
    background:#ccc;
    border-radius:10px;
}

/* IMAGE */
.modal-img{
    width:100%;
    max-height:260px;
    object-fit:cover;
    border-radius:14px;
    margin-bottom:15px;
}

/* CLOSE BUTTON (FINAL FIXED) */
.close{
    position:absolute;
    right:15px;
    top:15px;              /* ✅ inside box */
    background:#fff;
    border-radius:50%;
    width:32px;
    height:32px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
    cursor:pointer;
    z-index:10000;
    box-shadow:0 4px 12px rgba(0,0,0,0.25);
    transition:0.2s;
}
.close:hover{
    transform:scale(1.1);
}

/* INFO GRID */
.info-grid{
    display:grid;
    grid-template-columns: repeat(3,1fr);
    gap:15px;
    margin-top:15px;
}

/* BOX */
.info-box{
    background:#f9fafb;
    padding:12px;
    border-radius:12px;
    transition:0.2s;
}
.info-box:hover{
    background:#f1f5f9;
}

/* TEXT */
.info-box span{
    font-size:12px;
    color:#6b7280;
}
.info-box b{
    display:block;
    margin-top:6px;
    font-size:15px;
}

/* FULL WIDTH */
.info-box.full{
    grid-column: span 3;
}

/* ACTION BUTTONS */
.actions{
    margin-top:20px;
    display:flex;
    gap:10px;
}
.btn{
    padding:6px 14px;
    border-radius:20px;
    text-decoration:none;
    color:white;
    background:#7c3aed;
    font-size:12px;
}
.btn.disabled{
    background:#9ca3af;
}
.btn.receipt{
    background:#16a34a;
}
.btn.feedback{
    background:#f59e0b;
}

/* RESPONSIVE */
@media(max-width:600px){
    .info-grid{
        grid-template-columns:1fr;
    }
}
.filter-container{
    text-align:center;
    margin-bottom:30px;   /* 🔥 gap create karega */
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>My Bookings</h2>

<div class="filter-container">
<a href="?status=approved" class="filter-btn <?php echo ($filter=='approved')?'active':''; ?>">Approved</a>
<a href="?status=pending" class="filter-btn <?php echo ($filter=='pending')?'active':''; ?>">Pending</a>
</div>

<?php
if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

/* FOOD */
$food_names = "";
$food_images = [];

if(!empty($row['food_ids'])){
    $food_query = mysqli_query($conn,"
        SELECT menu, food_image 
        FROM food 
        WHERE food_id IN (" . $row['food_ids'] . ")
    ");

    while($f = mysqli_fetch_assoc($food_query)){
        $food_names .= $f['menu'] . ", ";
        $food_images[] = $f['food_image'];
    }

    $food_names = rtrim($food_names, ", ");
}

/* COVERAGE */
$coverage_names = "";

if(!empty($row['coverage_ids'])){
    $cov_query = mysqli_query($conn,"
        SELECT coverage_type 
        FROM coverage 
        WHERE coverage_id IN (" . $row['coverage_ids'] . ")
    ");

    while($c = mysqli_fetch_assoc($cov_query)){
        $coverage_names .= $c['coverage_type'] . ", ";
    }

    $coverage_names = rtrim($coverage_names, ", ");
}

$booking_id = $row['booking_id'];

/* PAYMENT */
$paidData = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) as total FROM payments WHERE booking_id = $booking_id
"));

$totalPaid = isset($paidData['total']) ? $paidData['total'] : 0;
$remaining = $row['total_price'] - $totalPaid;

$status = $row['payment_status'];

$event_date = $row['event_date'];
$today = date("Y-m-d");
$change_last_date = date("Y-m-d", strtotime($event_date . " -2 days"));
$is_edit_allowed = ($today <= $change_last_date);
?>

<!-- CARD -->
<div class="card" onclick="openModal(<?php echo $booking_id; ?>)">
    <div class="card-img">
        <img src="../uploads/images/events_images/<?php echo $row['image']; ?>">
    </div>
    <div class="card-content">
        <div class="event-name"><?php echo $row['event_name']; ?></div>
        <div class="event-date"><?php echo $event_date; ?></div>
    </div>
</div>

<div id="modal-<?php echo $booking_id; ?>" class="modal">
<div class="modal-box">

<span class="close" onclick="closeModal(<?php echo $booking_id; ?>)">&times;</span>

<img src="../uploads/images/events_images/<?php echo $row['image']; ?>" class="modal-img">

<h3><?php echo $row['event_name']; ?></h3>

<!-- ✅ SUMMARY (IMPORTANT - THIS WAS MISSING) -->
<div class="info-grid">

<div class="info-box">
<span>Package</span>
<b><?php echo $row['package_name']; ?></b>
</div>

<div class="info-box">
<span>Capacity</span>
<b><?php echo $row['capacity']; ?></b>
</div>

<div class="info-box">
<span>Event Date</span>
<b><?php echo $event_date; ?></b>
</div>

<div class="info-box">
<span>Total Price</span>
<b>₹ <?php echo number_format($row['total_price'],2); ?></b>
</div>

<div class="info-box">
<span>Advance Paid</span>
<b>₹ <?php echo number_format($totalPaid,2); ?></b>
</div>

<div class="info-box">
<span>Remaining</span>
<b>₹ <?php echo number_format($remaining,2); ?></b>
</div>

</div>

<hr>

<!-- ✅ DETAILS (ONLY ONCE — NO DUPLICATE) -->
<div class="info-grid">

<div class="info-box">
<span>Venue</span>
<b><?php echo $row['venue_name']; ?></b>
</div>

<div class="info-box">
<span>Seat</span>
<b><?php echo $row['seat_type']; ?></b>
</div>

<div class="info-box">
<span>Decoration</span>
<b><?php echo $row['decoration_name']; ?></b>
</div>

<div class="info-box">
<span>Coverage</span>
<b><?php echo !empty($coverage_names)?$coverage_names:'N/A'; ?></b>
</div>

<div class="info-box full">
<span>Food</span>
<b><?php echo !empty($food_names)?$food_names:'N/A'; ?></b>
</div>

</div>

<!-- ✅ IMAGES -->


<div class="actions">

<?php if($status != 'Pending'){ ?>

    <?php if($is_edit_allowed){ ?>
        <a href="edit_booking.php?id=<?php echo $booking_id; ?>" class="btn">Edit</a>
    <?php } else { ?>
        <a class="btn disabled">Edit Closed</a>
    <?php } ?>

    <a href="receipt.php?booking_id=<?php echo $booking_id; ?>" class="btn receipt">Receipt</a>

    <?php 
    // OPTIONAL: event complete hone ke baad hi feedback
    if($event_date < date("Y-m-d")){ ?>
        <a href="feedback.php?booking_id=<?php echo $booking_id; ?>" class="btn feedback">Feedback</a>
    <?php } ?>

<?php } else { ?>

    <a class="btn disabled">Pending Approval</a>

<?php } ?>

</div>
</div>
</div>

<?php
}
}else{
echo "<div class='card'>No bookings found</div>";
}
?>

</div>

<script>
function openModal(id){
    document.getElementById('modal-'+id).style.display = 'block';
}
function closeModal(id){
    document.getElementById('modal-'+id).style.display = 'none';
}
</script>

</body>
<?php include("../footer.php"); ?>
</html>