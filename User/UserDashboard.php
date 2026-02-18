<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>College Dashboard</title>

<style>
body{
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:#f2f8ff;
}

.container{
    max-width:900px;
    margin:50px auto;
    padding:30px;
}

.card{
    background:#ffffff;
    padding:30px;
    border-radius:8px;
    border-top:6px solid #4da6ff;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

h2{
    margin-top:0;
    color:#1f4fd8;
}

p{
    font-size:16px;
    color:#333;
}

.logout-btn{
    display:inline-block;
    margin-top:20px;
    padding:10px 18px;
    background:#4da6ff;
    color:white;
    text-decoration:none;
    border-radius:5px;
    transition:0.3s;
}

.logout-btn:hover{
    background:#1f8fff;
}
</style>

</head>
<body>

<div class="container">
    <div class="card">
        <h2>College Dashboard</h2>
        <p>Welcome <strong><?php echo $_SESSION['college_name']; ?></strong></p>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

</body>
</html>
