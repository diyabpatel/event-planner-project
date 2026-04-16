<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

/* EVENTS LIST */
$events = mysqli_query($conn,"SELECT * FROM events");

/* SELECTED EVENT */
$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : "";

/* FEEDBACK WITH JOIN */
if($event_id != ""){
    $feedbacks = mysqli_query($conn,"
        SELECT f.*, e.event_name
        FROM feedback f
        JOIN bookings b ON f.booking_id = b.booking_id
        JOIN events e ON b.event_id = e.event_id
        WHERE b.event_id = '$event_id'
    ");
}else{
    $feedbacks = mysqli_query($conn,"
        SELECT f.*, e.event_name
        FROM feedback f
        JOIN bookings b ON f.booking_id = b.booking_id
        JOIN events e ON b.event_id = e.event_id
    ");
}

/* TOP RATED EVENT */
$top = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT e.event_name, AVG(f.rating) as avg_rating, COUNT(*) as total
FROM feedback f
JOIN bookings b ON f.booking_id = b.booking_id
JOIN events e ON b.event_id = e.event_id
GROUP BY b.event_id
ORDER BY avg_rating DESC, total DESC
LIMIT 1
"));

/* AVG RATING */
$avg = null;
if($event_id != ""){
    $avg = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT AVG(f.rating) as avg_rating
    FROM feedback f
    JOIN bookings b ON f.booking_id = b.booking_id
    WHERE b.event_id='$event_id'
    "));
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Feedback</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

.content{
    margin-left:250px;
    padding:100px 40px 40px;
}

h2{
    color:#5b21b6;
}

/* TOP BOX */
.top{
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:#fff;
    padding:18px;
    border-radius:15px;
    margin-bottom:25px;
    box-shadow:0 8px 25px rgba(124,58,237,0.3);
}

.trophy{
    margin-right:8px;
}

/* DROPDOWN */
select{
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
    margin-bottom:20px;
}

/* AVG */
.avg{
    background:#ede9fe;
    padding:10px 15px;
    border-radius:10px;
    display:inline-block;
    margin-bottom:20px;
    font-weight:600;
}

/* CARD */
.card{
    background:#fff;
    padding:20px;
    border-radius:15px;
    margin-bottom:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.event-name{
    font-weight:600;
    color:#5b21b6;
}

/* STARS */
.star{
    color:#fbbf24;
    font-size:16px;
}

.star-gray{
    color:#d1d5db;
}

.rating{
    margin:5px 0;
}

.comment{
    font-size:14px;
    color:#374151;
}

</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="content">

<h2>Manage Feedback</h2>

<!-- TOP EVENT -->
<div class="top">
    <span class="trophy">🏆</span>
    Top Rated Event: 
    <b><?php echo $top['event_name']; ?></b> ⭐ 
    (<?php echo round($top['avg_rating'],1); ?>)
</div>

<!-- DROPDOWN -->
<form method="GET">
<select name="event_id" onchange="this.form.submit()">
<option value="">All Events</option>

<?php while($e = mysqli_fetch_assoc($events)){ ?>
<option value="<?php echo $e['event_id']; ?>" <?php if($event_id==$e['event_id']) echo "selected"; ?>>
<?php echo $e['event_name']; ?>
</option>
<?php } ?>

</select>
</form>

<!-- AVG -->
<?php if($avg){ ?>
<div class="avg">
⭐ Average Rating: <?php echo round($avg['avg_rating'],1); ?>
</div>
<?php } ?>

<!-- FEEDBACK -->
<?php while($f = mysqli_fetch_assoc($feedbacks)){ ?>
<div class="card">

<div class="event-name"><?php echo $f['event_name']; ?></div>

<div class="rating">
<?php
for($i=1;$i<=5;$i++){
    if($i <= $f['rating']){
        echo "<span class='star'>★</span>";
    } else {
        echo "<span class='star-gray'>★</span>";
    }
}
?>
</div>

<div class="comment">
<?php echo $f['comment']; ?>
</div>

</div>
<?php } ?>

</div>

</body>
</html>