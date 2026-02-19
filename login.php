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

        if($user['user_id'] == 1){
            header("Location: admin/admindashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
    else{
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
    font-family: "Segoe UI", sans-serif;
    background:linear-gradient(135deg,#eaf1ff,#f8fbff);
}

.login-wrapper{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    width:380px;
    background:#ffffff;
    padding:35px 30px;
    border-radius:14px;
    box-shadow:0 18px 40px rgba(0,0,0,0.12);
}

.login-card h2{
    text-align:center;
    margin-bottom:25px;
    color:#1f4fd8;
    letter-spacing:0.5px;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#444;
}

.form-group input{
    width:100%;
    padding:11px;
    border:1px solid #d6e2ff;
    border-radius:8px;
    font-size:14px;
    transition:0.3s;
}

.form-group input:focus{
    outline:none;
    border-color:#1f4fd8;
    box-shadow:0 0 0 3px rgba(31,79,216,0.15);
}

.login-btn{
    width:100%;
    padding:12px;
    background:#1f4fd8;
    color:white;
    border:none;
    border-radius:8px;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover{
    background:#163bb0;
}

.extra-links{
    margin-top:18px;
    text-align:center;
    font-size:14px;
}

.extra-links a{
    color:#1f4fd8;
    text-decoration:none;
    font-weight:500;
}

.extra-links a:hover{
    text-decoration:underline;
}
</style>

</head>
<body>

<div class="login-wrapper">

    <div class="login-card">

        <h2>Login</h2>

        <form method="post">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="login" class="login-btn">
                Login
            </button>

        </form>

        <div class="extra-links">
            Don’t have an account?
            <a href="register.php">Create Account</a>
        </div>

    </div>

</div>

</body>
</html>
