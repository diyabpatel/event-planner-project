<?php
session_start();
include("../db.php");

/* Fetch Convocation Event ID dynamically */
$event_query = mysqli_query($conn,"SELECT event_id FROM events WHERE event_name='Farewell Party'");
$event = mysqli_fetch_assoc($event_query);

if(!$event){
    die("farewell party event not found in database");
}

$convocation_id = $event['event_id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Farewell Party</title>

<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
}

/* Hero Section */
.hero{
    height:90vh;
    background:url('../uploads/images/bg.jpg') no-repeat center center/cover;
    position:relative;
    display:flex;
    justify-content:center;
    align-items:center;
}

.overlay{
    background:rgba(0,0,0,0.6);
    padding:50px;
    border-radius:15px;
    text-align:center;
    color:white;
}

.overlay h1{
    font-size:55px;
    margin-bottom:20px;
}

.overlay p{
    font-size:18px;
    margin-bottom:30px;
}

/* Button */
.btn{
    background:#ff6b6b;
    padding:12px 30px;
    border:none;
    border-radius:30px;
    color:white;
    font-size:18px;
    cursor:pointer;
    text-decoration:none;
}

.btn:hover{
    background:#ff4757;
}
</style>
</head>

<body>

<?php include("../navbar.php"); ?>

<div class="hero">
    <div class="overlay">
        <h1>Farewell Party</h1>
        <p>Experience music, dance, art, and farewell celebrations.</p>

        <!-- CORRECT LINK -->
        <a href="../User/book_event.php?event_id=<?php echo $convocation_id; ?>" class="btn">
            Book Now
        </a>
    </div>
</div>

</body>
</html>