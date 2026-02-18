<?php
session_start();
include("db.php");

if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['college_name'] = $user['college_name'];

        // Redirect based on user role
        if($user['user_id'] == 1)
        {
            header("Location: admin/admindashboard.php");
        }
        else
        {
            header("Location: index.php");   // redirect to main homepage
        }

        exit();
    }
    else
    {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>

body{
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background:#f2f8ff;
}

.container{
    max-width:400px;
    margin:80px auto;
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

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:14px;
}

input:focus{
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

<h2>Login</h2>

<form method="post">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

</form>

<a href="register.php">Create new account</a>

</div>

</div>

</body>
</html>
