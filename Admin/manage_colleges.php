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

/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

/* BODY */
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* MAIN CONTENT */
.main-content{
margin-left:260px;
padding:30px;
}

/* HEADER */
.header{
background:white;
color:#5b21b6;
padding:20px 25px;
font-size:22px;
font-weight:600;
border-radius:16px;
box-shadow:0 10px 30px rgba(91,33,182,0.15);
margin-bottom:25px;
}

/* CONTAINER */
.container{
width:100%;
}

/* CARD */
.college-card{
background:white;
border-radius:20px;
padding:25px;
margin-bottom:25px;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
display:grid;
grid-template-columns:1fr 1fr;
gap:25px;
align-items:center;
transition:0.3s;
border:1px solid #e9d5ff;
}

/* HOVER */
.college-card:hover{
transform:translateY(-6px);
box-shadow:0 20px 50px rgba(91,33,182,0.25);
}

/* INFO */
.college-info h3{
margin-top:0;
color:#5b21b6;
font-size:20px;
margin-bottom:10px;
}

.college-info p{
margin:6px 0;
font-size:14px;
color:#444;
}

/* MAP */
.map{
width:100%;
height:230px;
border-radius:16px;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

/* IFRAME */
iframe{
width:100%;
height:100%;
border:0;
}

/* RESPONSIVE */
@media(max-width:900px){
.college-card{
grid-template-columns:1fr;
}
}

</style>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

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
</div>
</body>
</html>