<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Tech Fest</title>

<style>

body{
    margin:0;
    font-family:Arial;
}

.hero{
    background-image:url("images/techfest.jpg");
    background-size:cover;
    background-position:center;
    height:400px;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    font-size:50px;
    font-weight:bold;
}

.container{
    padding:40px;
}

.gallery{
    display:flex;
    gap:20px;
}

.gallery img{
    width:300px;
    border-radius:10px;
}

.packages{
    margin-top:40px;
}

.card{
    background:white;
    padding:20px;
    margin:10px;
    display:inline-block;
    width:200px;
    box-shadow:0px 0px 10px gray;
}

button{
    padding:10px;
    background:#3498db;
    color:white;
    border:none;
    cursor:pointer;
}

</style>

</head>

<body>

<div class="hero">
Tech Fest
</div>

<div class="container">

<h2>About Tech Fest</h2>

<p>
Tech Fest includes coding competitions, hackathons, robotics competitions, and technical workshops.
</p>

<h2>Gallery</h2>

<div class="gallery">

<img src="images/tech1.jpg">
<img src="images/tech2.jpg">
<img src="images/tech3.jpg">

</div>

<h2>Packages</h2>

<div class="packages">

<?php

$query="SELECT * FROM packages 
JOIN events ON packages.event_id=events.event_id
WHERE events.event_name='Tech Fest'";

$result=mysqli_query($conn,$query);

while($row=mysqli_fetch_assoc($result))
{
    echo '
    <div class="card">
    <h3>'.$row['package_name'].'</h3>
    <button onclick="location.href=\'package_details.php?package_id='.$row['package_id'].'\'">
    View Details
    </button>
    </div>
    ';
}

?>

</div>

</div>

</body>
</html>
