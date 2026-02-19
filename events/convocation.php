<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Convocation Event</title>

<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
}

/* Navbar */
.navbar{
    background:#2c3e50;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar h2{
    color:white;
    margin:0;
}

.nav-links a{
    color:white;
    text-decoration:none;
    margin-left:25px;
    font-weight:bold;
}

.nav-links a:hover{
    color:#00c6ff;
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

<!-- Navbar -->
<div class="navbar">
    <h2>Event Management</h2>

    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="../User/my_bookings.php">My Bookings</a>
        <?php if(isset($_SESSION['user_id'])){ ?>
            <a href="../logout.php">Logout</a>
        <?php } else { ?>
            <a href="../login.php">Login</a>
        <?php } ?>
    </div>
</div>

<!-- Hero Section -->
<div class="hero">
    <div class="overlay">
        <h1>Convocation Ceremony</h1>
        <p>Celebrate achievements and honor graduates in a grand convocation ceremony.</p>

        <a href="../User/book_event.php?event_id=2" class="btn">Book Now</a>
    </div>
</div>

</body>
</html>
