<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== SAFE QUERY WITH CORRECT COLUMN ===== */
$sql = "
SELECT 
    b.booking_id, 
    b.notification, 
    b.is_read, 
    b.booking_date, 
    b.payment_status,
    e.image
FROM bookings b
LEFT JOIN events e ON b.event_id = e.event_id
WHERE b.user_id='$user_id' 
AND b.notification IS NOT NULL 
AND b.notification!=''
ORDER BY b.booking_id DESC
";

$query = mysqli_query($conn, $sql);

if(!$query){
    die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications</title>

<style>

/* ===== BODY ===== */
body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:linear-gradient(135deg,#f5f3ff,#eef2ff);
color:#1f2937;
}

/* ===== CONTAINER ===== */
.container{
max-width:780px;
margin:40px auto;
padding:0 15px;
}

/* ===== TITLE ===== */
h2{
margin-bottom:25px;
font-size:28px;
font-weight:700;
}

/* ===== CARD ===== */
.card{
position:relative;
background:rgba(255,255,255,0.75);
backdrop-filter:blur(14px);
border-radius:16px;
padding:16px;
margin-bottom:18px;

display:flex;
gap:15px;
align-items:center;

border:1px solid rgba(255,255,255,0.4);
box-shadow:0 10px 25px rgba(0,0,0,0.05);

transition:0.3s ease;
}

/* LEFT ACCENT */
.card::before{
content:"";
position:absolute;
left:0;
top:0;
bottom:0;
width:5px;
border-radius:16px 0 0 16px;
}

.card.confirm::before{
background:linear-gradient(#22c55e,#4ade80);
}

.card.reject::before{
background:linear-gradient(#ef4444,#f87171);
}

/* UNREAD */
.card.unread{
transform:scale(1.01);
box-shadow:0 12px 30px rgba(99,102,241,0.15);
}

/* HOVER */
.card:hover{
transform:translateY(-5px);
box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

/* IMAGE */
.event-img{
width:70px;
height:70px;
border-radius:12px;
object-fit:cover;
flex-shrink:0;
border:2px solid rgba(255,255,255,0.6);
box-shadow:0 6px 15px rgba(0,0,0,0.08);
}

/* ICON */
.icon{
width:38px;
height:38px;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
font-size:16px;
flex-shrink:0;
}

.icon.success{
background:#dcfce7;
color:#16a34a;
}

.icon.error{
background:#fee2e2;
color:#dc2626;
}

/* CONTENT */
.content{
flex:1;
}

.msg{
font-size:15px;
font-weight:500;
margin-bottom:6px;
line-height:1.6;
}

.meta{
font-size:12px;
color:#6b7280;
}

/* BUTTON */
.btn{
margin-top:10px;
display:inline-block;
padding:8px 14px;
font-size:12px;
border-radius:10px;
text-decoration:none;
font-weight:600;

background:linear-gradient(135deg,#6366f1,#8b5cf6);
color:white;

transition:0.3s;
}

.btn:hover{
transform:translateY(-1px);
box-shadow:0 10px 25px rgba(99,102,241,0.3);
}

/* EMPTY */
.empty{
text-align:center;
margin-top:60px;
color:#6b7280;
font-size:15px;
}

</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>Notifications</h2>

<?php
if($query && mysqli_num_rows($query) > 0){

    $ids = [];

    while($row = mysqli_fetch_assoc($query)){

        if($row['is_read'] == 0){
            $ids[] = $row['booking_id'];
        }

        $statusClass = ($row['payment_status']=="Rejected") ? "reject" : "confirm";
        $readClass = ($row['is_read']==0) ? "unread" : "";

        echo "<div class='card $statusClass $readClass'>";

        /* IMAGE (CORRECT PATH FIXED) */
        $imgPath = "../uploads/images/events_images/" . $row['image'];

        if(!empty($row['image']) && file_exists($imgPath)){
            echo "<img src='$imgPath' class='event-img'>";
        } else {
            echo "<img src='../images/default.jpg' class='event-img'>";
        }

        /* ICON */
        if($row['payment_status']=="Rejected"){
            echo "<div class='icon error'>⚠</div>";
        } else {
            echo "<div class='icon success'>✔</div>";
        }

        echo "<div class='content'>";

        /* MESSAGE */
        echo "<div class='msg'>".$row['notification']."</div>";

        /* DATE */
        echo "<div class='meta'>".$row['booking_date']."</div>";

        /* BUTTON */
        if($row['payment_status']=="Rejected"){
            echo "
            <a href='reupload_payment.php?booking_id=".$row['booking_id']."' class='btn'>
                Re-upload Documents
            </a>
            ";
        }

        echo "</div>";
        echo "</div>";
    }

    /* MARK AS READ */
    if(!empty($ids)){
        $id_list = implode(",", $ids);
        mysqli_query($conn,"
        UPDATE bookings 
        SET is_read=1 
        WHERE booking_id IN ($id_list)
        ");
    }

}else{
    echo "<div class='empty'>No notifications yet</div>";
}
?>

</div>

</body>
<?php include("../footer.php"); ?>
</html>