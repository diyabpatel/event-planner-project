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
    font-family: Arial;
    margin: 0;
    background-color: #f4f6f8;
}

/* HEADER */

header {
    background-color: #2c3e50;
    color: white;
    padding: 15px;
}

header h1 {
    display: inline-block;
    margin: 0;
}

nav {
    float: right;
}

nav a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

/* Dropdown */

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown > a::after {
    content: "";
    display: inline-block;
    margin-left: 6px;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid white;
    vertical-align: middle;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 200px;
    box-shadow: 0px 0px 10px gray;
    z-index: 999;
}

.dropdown-content a {
    color: black;
    padding: 10px;
    display: block;
    text-decoration: none;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}

/* HERO SECTION */

.hero-background {
    height: calc(100vh - 70px);
    background: url('uploads/images/bg.jpg') no-repeat center center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-text {
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
}

.hero-text h2{
    font-size: 36px;
    margin: 0;
}

.hero-text p{
    margin-top: 10px;
    font-size: 18px;
}
</style>
</head>

<body>

<header>

<h1>Event Management System</h1>

<nav>

<a href="index.php">Home</a>

<div class="dropdown">
<a href="#">Events</a>
<div class="dropdown-content">
<?php
$query = "SELECT * FROM events ORDER BY event_name ASC";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result))
{
    echo '<a href="'.$row['page'].'">'.$row['event_name'].'</a>';
}
?>
</div>
</div>

<a href="gallery.php">Work Gallery</a>

<?php
if(isset($_SESSION['user_id']))
{
    echo '<a href="User/profile.php">Profile</a>';
    echo '<a href="User/my_bookings.php">My Bookings</a>';
    echo '<a href="logout.php">Logout</a>';  // ✅ Corrected
}
else
{
    echo '<a href="login.php">Login</a>';
    echo '<a href="register.php">Register</a>';
}
?>

</nav>

<div style="clear:both;"></div>

</header>

<div class="hero-background">
<div class="hero-text">
<h2>College Event Package Management System</h2>
<p>Book complete event packages with venue, food, decoration, seating, photography, and videography.</p>
</div>
</div>

</body>
</html>
