<?php
session_start();

if(!isset($_SESSION['user_id']))
{
header("Location: ../login.php");
exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];
$event_id = intval($_GET['event_id']);

if(isset($_POST['book']))
{

$package_id = $_POST['package_id'];
$event_date = $_POST['event_date'];

$total_price = 0;

$query="INSERT INTO bookings
(user_id,event_id,package_id,event_date,total_price)
VALUES
('$user_id','$event_id','$package_id','$event_date','$total_price')";

mysqli_query($conn,$query);

echo "<script>alert('Booking Successful');</script>";

}

$package_query="SELECT * FROM packages WHERE event_id=$event_id";
$packages=mysqli_query($conn,$package_query);

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Book Event</title>

<style>

body{
font-family:Arial;
background:#f4f6f8;
}

.container{
width:500px;
margin:auto;
background:white;
padding:20px;
margin-top:50px;
box-shadow:0px 0px 10px gray;
}

select,input,button{
width:100%;
padding:10px;
margin:10px 0;
}

button{
background:#3498db;
color:white;
border:none;
cursor:pointer;
}

</style>

</head>

<body>

<div class="container">

<h2>Book Event</h2>

<form method="POST">

<select name="package_id" required>

<option value="">Select Package</option>

<?php

while($row=mysqli_fetch_assoc($packages))
{
echo "<option value='".$row['package_id']."'>".$row['package_name']."</option>";
}

?>

</select>


<input type="date" name="event_date" required>

<button type="submit" name="book">

Confirm Booking

</button>

</form>

</div>

</body>
</html>
