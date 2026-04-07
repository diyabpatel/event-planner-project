<?php
include("../db.php");

if(!isset($_GET['event_id'])){
    echo "Invalid Event";
    exit();
}

$event_id = (int)$_GET['event_id'];

/* EVENT INFO */
$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE event_id=$event_id"));

/* TOTAL BOOKINGS */
$b = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM bookings 
WHERE event_id=$event_id
"));

/* TOTAL REVENUE */
$r = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total_price),0) as revenue 
FROM bookings 
WHERE event_id=$event_id
"));

/* AVG RATING */
$rating = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(AVG(rating),0) as avg_rating 
FROM feedback f
JOIN bookings b ON f.booking_id = b.booking_id
WHERE b.event_id=$event_id
"));

/* FEEDBACKS */
$feedbacks = mysqli_query($conn,"
SELECT f.*, b.event_id 
FROM feedback f
JOIN bookings b ON f.booking_id = b.booking_id
WHERE b.event_id=$event_id
ORDER BY f.created_at DESC
");

/* RECENT BOOKINGS */
$bookings = mysqli_query($conn,"
SELECT * FROM bookings 
WHERE event_id=$event_id
ORDER BY booking_id DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Event Insights</title>

<style>
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
font-family:'Poppins',sans-serif;
}

.main{
margin-left:260px;
padding:30px;
}

h2{
text-align:center;
color:#5b21b6;
margin-bottom:25px;
}

/* STATS */
.stats{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:20px;
border-radius:16px;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.card h3{
color:#6d28d9;
margin-bottom:10px;
}

.card p{
font-size:22px;
font-weight:600;
}

/* SECTION */
.section{
background:white;
padding:20px;
border-radius:16px;
margin-bottom:25px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.section h3{
margin-bottom:15px;
color:#5b21b6;
}

/* FEEDBACK */
.feedback{
border-bottom:1px solid #eee;
padding:10px 0;
}

.feedback:last-child{
border:none;
}

/* TABLE */
table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:left;
}
</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main">

<h2><?php echo $event['event_name']; ?> Insights</h2>

<!-- STATS -->
<div class="stats">

<div class="card">
<h3>Total Bookings</h3>
<p><?php echo $b['total']; ?></p>
</div>

<div class="card">
<h3>Total Revenue</h3>
<p>₹<?php echo $r['revenue']; ?></p>
</div>

<div class="card">
<h3>Average Rating</h3>
<p><?php echo round($rating['avg_rating'],1); ?>/5</p>
</div>

<div class="card">
<h3>Total Feedbacks</h3>
<p><?php echo mysqli_num_rows($feedbacks); ?></p>
</div>

</div>

<!-- FEEDBACK SECTION -->
<div class="section">
<h3>Customer Feedback</h3>

<?php while($f=mysqli_fetch_assoc($feedbacks)){ ?>
<div class="feedback">
⭐ <?php echo $f['rating']; ?>/5  
<p><?php echo $f['comment']; ?></p>
</div>
<?php } ?>

</div>

<!-- BOOKINGS -->
<div class="section">
<h3>Recent Bookings</h3>

<table>
<tr>
<th>ID</th>
<th>Date</th>
<th>Capacity</th>
<th>Total</th>
</tr>

<?php while($bk=mysqli_fetch_assoc($bookings)){ ?>
<tr>
<td><?php echo $bk['booking_id']; ?></td>
<td><?php echo $bk['event_date']; ?></td>
<td><?php echo $bk['capacity']; ?></td>
<td>₹<?php echo $bk['total_price']; ?></td>
</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>