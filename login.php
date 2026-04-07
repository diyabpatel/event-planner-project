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

        /* ✅ CLEAR OLD SESSION (VERY IMPORTANT FIX) */
        session_unset();
        session_destroy();
        session_start();

        /* ✅ SECURE SESSION */
        session_regenerate_id(true);

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
<title>College Event Login</title>

<style>
    
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:'Poppins',sans-serif;
}

/* 🌟 BACKGROUND */
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
    radial-gradient(circle at top,#ffffff,#f3f0ff 40%,#e4ddff 70%,#d6ccff);
}

/* 💎 GLASS CARD */
.login-card{
    width:390px;
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(20px);
    border-radius:22px;
    padding:42px 34px;
    box-shadow:
    0 25px 60px rgba(111,66,193,0.25),
    0 0 25px rgba(140,90,255,0.3),
    inset 0 0 0 1px rgba(255,255,255,0.8);
    animation:fadeIn 0.8s ease;
    position:relative;
}

/* ✨ GLOW BORDER EFFECT */
.login-card::before{
    content:"";
    position:absolute;
    inset:0;
    border-radius:22px;
    padding:1px;
    background:linear-gradient(135deg,#a084ff,#7f5cff,#c4b5fd);
    -webkit-mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0);
    -webkit-mask-composite:xor;
    mask-composite:exclude;
    
    pointer-events:none; /* 🔥 IMPORTANT FIX */
}

/* 🔥 ANIMATION */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(25px)}
    to{opacity:1;}
}

/* 🏷 TITLE */
.login-card h2{
    text-align:center;
    margin-bottom:28px;
    font-weight:600;
    font-size:24px;
    background:linear-gradient(90deg,#6f42c1,#9d7bff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* 📥 INPUTS */
.form-group{
    margin-bottom:20px;
}

.form-group label{
    font-size:14px;
    color:#5a4a9c;
    margin-bottom:6px;
    display:block;
}

.form-group input{
    width:100%;
    padding:13px;
    border-radius:12px;
    border:none;
    background:#ffffff;
    font-size:14px;
    box-shadow:
    0 5px 15px rgba(0,0,0,0.08),
    inset 0 0 0 1px #e0d8ff;
    transition:0.3s;
}

/* 🌟 INPUT GLOW */
.form-group input:focus{
    outline:none;
    box-shadow:
    0 0 0 2px #a084ff,
    0 0 18px rgba(160,132,255,0.5);
}

/* 🚀 BUTTON */
.login-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    font-size:15px;
    cursor:pointer;
    margin-top:10px;
    color:white;
    font-weight:500;
    background:linear-gradient(135deg,#7f5cff,#6f42c1);
    box-shadow:
    0 12px 30px rgba(111,66,193,0.5),
    0 0 20px rgba(140,90,255,0.6);
    transition:.35s;
}

/* 💥 BUTTON HOVER */
.login-btn:hover{
    transform:translateY(-3px) scale(1.02);
    box-shadow:
    0 18px 45px rgba(111,66,193,0.7),
    0 0 30px rgba(140,90,255,0.9);
}

/* 🔗 LINKS */
.extra-links{
    margin-top:22px;
    text-align:center;
    font-size:14px;
    color:#5a4a9c;
}

.extra-links a{
    color:#7f5cff;
    text-decoration:none;
    font-weight:600;
}

.extra-links a:hover{
    text-decoration:underline;
}

/* 📱 RESPONSIVE */
@media(max-width:480px){
    .login-card{
        width:92%;
    }
}
</style>

</head>
<body>

<div class="login-card">

    <h2>College Event Login</h2>

    <form method="post">

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="college@email.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
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

</body>
</html>