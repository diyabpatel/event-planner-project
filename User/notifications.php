<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===== FETCH NOTIFICATIONS ===== */

$query = mysqli_query($conn,"
SELECT booking_id, notification, is_read, booking_date, payment_status
FROM bookings
WHERE user_id='$user_id' 
AND notification IS NOT NULL 
AND notification!=''
ORDER BY booking_id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications</title>

<style>

body{
margin:0;
font-family:'Segoe UI',sans-serif;
background:linear-gradient(135deg,#0b0f1a,#121a2e,#1a2742);
color:#eaeaff;
}

.container{
max-width:800px;
margin:50px auto;
padding:25px;
}

h2{
text-align:center;
margin-bottom:35px;
font-size:28px;
background:linear-gradient(90deg,#7aa2ff,#9bb6ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.card{
background:rgba(255,255,255,0.06);
backdrop-filter:blur(18px);
padding:20px;
margin-bottom:18px;
border-radius:14px;
border-left:5px solid #3b82f6;
transition:0.3s;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.card:hover{
transform:translateY(-4px);
}

.card.unread{
border-left:5px solid #ef4444;
}

.msg{
font-size:15px;
margin-bottom:6px;
line-height:1.5;
}

.time{
font-size:12px;
color:#94a3b8;
}

/* BUTTON */
.btn{
display:inline-block;
margin-top:10px;
padding:8px 16px;
background:linear-gradient(135deg,#ef4444,#dc2626);
color:white;
text-decoration:none;
border-radius:6px;
font-size:13px;
transition:0.3s;
}

.btn:hover{
transform:translateY(-2px);
}

/* STATUS TAG */
.tag{
display:inline-block;
margin-top:8px;
padding:4px 10px;
border-radius:20px;
font-size:11px;
}

.confirmed{
background:#16a34a;
}

.rejected{
background:#ef4444;
}

.empty{
text-align:center;
margin-top:60px;
color:#94a3b8;
font-size:15px;
}

</style>

</head>

<body>

<?php include("../navbar.php"); ?>

<div class="container">

<h2>Notifications</h2>

<?php
if(mysqli_num_rows($query) > 0){

    $ids = [];

    while($row = mysqli_fetch_assoc($query)){

        /* collect unread ids */
        if($row['is_read'] == 0){
            $ids[] = $row['booking_id'];
        }

        $class = ($row['is_read'] == 0) ? "card unread" : "card";

        echo "<div class='$class'>";

        /* MESSAGE */
        echo "<div class='msg'>".$row['notification']."</div>";

        /* STATUS TAG */
        if($row['payment_status'] == "Rejected"){
            echo "<span class='tag rejected'>Action Required</span>";
        } else {
            echo "<span class='tag confirmed'>Confirmed</span>";
        }

        /* TIME */
        echo "<div class='time'>".$row['booking_date']."</div>";

        /* ONLY SHOW BUTTON FOR REJECTED */
        if($row['payment_status'] == "Rejected"){
            echo "
            <a href='reupload_payment.php?booking_id=".$row['booking_id']."' class='btn'>
                Re-upload Documents
            </a>
            ";
        }

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
</html>