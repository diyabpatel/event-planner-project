<?php
session_start();
include("../db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../login.php");
exit();
}

$query = mysqli_query($conn,"SELECT * FROM users WHERE user_id != 1");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Manage Colleges</title>

<style>

body{
font-family:Segoe UI;
margin:0;
background:#f1f5f9;
}

.header{
background:#2563eb;
color:white;
padding:18px 30px;
font-size:22px;
font-weight:600;
}

.container{
max-width:1200px;
margin:auto;
padding:30px;
}

.college-card{
background:white;
border-radius:12px;
padding:20px;
margin-bottom:25px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
align-items:center;
}

.college-info h3{
margin-top:0;
color:#1e3a8a;
}

.college-info p{
margin:6px 0;
}

.map{
width:100%;
height:220px;
border-radius:10px;
overflow:hidden;
}

iframe{
width:100%;
height:100%;
border:0;
}

</style>

</head>

<body>

<div class="header">
Manage Colleges
</div>

<div class="container">

<?php

while($row=mysqli_fetch_assoc($query))
{

$location = urlencode($row['college_name']." ".$row['city']." ".$row['state']);

echo "

<div class='college-card'>

<div class='college-info'>

<h3>".$row['college_name']."</h3>

<p><b>Email:</b> ".$row['email']."</p>
<p><b>Phone:</b> ".$row['phone']."</p>
<p><b>Address:</b> ".$row['address']."</p>
<p><b>City:</b> ".$row['city']."</p>
<p><b>State:</b> ".$row['state']."</p>

</div>

<div class='map'>

<iframe
src='https://maps.google.com/maps?q=$location&output=embed'>
</iframe>

</div>

</div>

";

}

?>

</div>

</body>
</html>