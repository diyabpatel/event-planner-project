<?php
include("db.php");

if(isset($_POST['signup'])){
    $college_name = $_POST['college_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    $password = md5($_POST['password']);

    $query = "INSERT INTO users 
    (college_name,email,password,phone,address,city,state)
    VALUES
    ('$college_name','$email','$password','$phone','$address','$city','$state')";

    if(mysqli_query($conn,$query)){
        echo "<script>alert('Signup Successful'); window.location='login.php';</script>";
    } else {
        echo "Error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>College Signup</title>

<style>
body{
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:#f2f8ff;
}

.container{
    max-width:450px;
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
    text-align:center;
    color:#1f4fd8;
}

input, textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:14px;
}

input:focus, textarea:focus{
    outline:none;
    border-color:#4da6ff;
}

button{
    width:100%;
    padding:10px;
    margin-top:10px;
    background:#4da6ff;
    color:white;
    border:none;
    border-radius:5px;
    font-size:15px;
    cursor:pointer;
}

button:hover{
    background:#1f8fff;
}

a{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#1f4fd8;
    text-decoration:none;
}

a:hover{
    text-decoration:underline;
}
</style>

</head>
<body>

<div class="container">
    <div class="card">
        <h2> Signup</h2>

        <form method="post">
            <input type="text" name="college_name" placeholder="College Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <textarea name="address" placeholder="Address"></textarea>
            <input type="text" name="city" placeholder="City">
            <input type="text" name="state" placeholder="State">
            <button type="submit" name="signup">Signup</button>
        </form>

        <a href="login.php">Already have account? Login</a>
    </div>
</div>

</body>
</html>
