<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>College Event Management System</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f6f8;
}

/* HERO SECTION ONLY (Navbar CSS removed) */

.hero-background {
    height: calc(100vh - 60px);
    background: url('uploads/images/bg.jpg') no-repeat center center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-text {
    background: rgba(0,0,0,0.55);
    color: white;
    padding: 35px;
    border-radius: 12px;
    text-align: center;
    max-width: 700px;
}

.hero-text h2{
    font-size: 38px;
    margin: 0;
}

.hero-text p{
    margin-top: 15px;
    font-size: 18px;
    line-height: 1.6;
}
</style>

</head>

<body>

<!-- ✅ NAVBAR INCLUDE -->
<?php include("navbar.php"); ?>

<!-- ✅ HERO SECTION -->
<div class="hero-background">

    <div class="hero-text">
        <h2>College Event Package Management System</h2>
        <p>
            Book complete event packages with venue, food, decoration,
            seating, photography, and videography.
        </p>
    </div>

</div>

</body>
</html>
