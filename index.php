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
            display: inline-block;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Dropdown container */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown button */
        .dropdown > a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        /* CSS Arrow (fix encoding issue) */
        .dropdown > a::after {
            content: "";
            display: inline-block;
            margin-left: 6px;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid white;
            vertical-align: middle;
        }

        /* Dropdown content */
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
            margin-left: 0;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Show dropdown */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .hero {
            background-color: #3498db;
            color: white;
            padding: 60px;
            text-align: center;
        }

        .hero h2 {
            font-size: 36px;
        }

        .container {
            padding: 40px;
            text-align: center;
        }

        .packages {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            padding: 20px;
            width: 250px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px gray;
        }

        .card h3 {
            color: #2c3e50;
        }

        .card button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }

        .card button:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 40px;
        }

    </style>

</head>

<body>

<header>

    <h1>Event Management System</h1>

    <nav>

        <a href="index.php">Home</a>

        <!-- Events Dropdown -->
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
            echo '<a href="dashboard.php">Dashboard</a>';
            echo '<a href="logout.php">Logout</a>';
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


<div class="hero">

    <h2>College Event Package Management System</h2>

    <p>
        Book complete event packages with venue, food, decoration, seating, photography, and videography.
    </p>

</div>


<div class="container">

    <h2>Our Packages</h2>

    <div class="packages">

        <div class="card">
            <h3>Basic Package</h3>
            <p>Affordable package with essential event arrangements.</p>
            <button onclick="location.href='index.php'">View Events</button>
        </div>

        <div class="card">
            <h3>Standard Package</h3>
            <p>Best package for medium scale college events.</p>
            <button onclick="location.href='index.php'">View Events</button>
        </div>

        <div class="card">
            <h3>Premium Package</h3>
            <p>Complete premium event management solution.</p>
            <button onclick="location.href='index.php'">View Events</button>
        </div>

    </div>

</div>


<footer>

    <p>© 2026 College Event Management System</p>

</footer>


</body>
</html>
