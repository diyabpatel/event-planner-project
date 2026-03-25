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
*{box-sizing:border-box}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    min-height:100vh;
    background:
    radial-gradient(circle at top,#1e2a5a,#0b1020 70%);
    display:flex;
    justify-content:center;
    align-items:center;
}

/* GLASS CARD */
.login-card{
    width:380px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(18px);
    border-radius:18px;
    padding:38px 32px;
    box-shadow:
    0 25px 60px rgba(0,0,0,0.7),
    inset 0 0 0 1px rgba(255,255,255,0.15);
    animation:fadeIn 0.9s ease;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(30px)}
    to{opacity:1;transform:none}
}

/* TITLE */
.login-card h2{
    text-align:center;
    margin-bottom:26px;
    font-weight:600;
    letter-spacing:0.6px;
    background:linear-gradient(90deg,#9bb6ff,#e0e7ff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* INPUTS */
.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    font-size:14px;
    color:#eaf0ff;
    opacity:.9;
}

.form-group input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:none;
    font-size:14px;
    background:rgba(0,0,0,0.45);
    color:white;
    box-shadow:inset 0 0 0 1px rgba(255,255,255,0.2);
    transition:0.3s;
}

.form-group input:focus{
    outline:none;
    box-shadow:
    inset 0 0 0 1px #7aa2ff,
    0 0 0 3px rgba(122,162,255,0.25);
}

/* BUTTON */
.login-btn{
    width:100%;
    padding:13px;
    border:none;
    border-radius:30px;
    font-size:15px;
    cursor:pointer;
    margin-top:10px;
    background:linear-gradient(135deg,#7aa2ff,#4f7cff);
    color:white;
    box-shadow:0 15px 35px rgba(122,162,255,0.6);
    transition:.35s;
}

.login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 22px 45px rgba(122,162,255,0.8);
}

/* EXTRA LINKS */
.extra-links{
    margin-top:20px;
    text-align:center;
    font-size:14px;
    color:#eaf0ff;
}

.extra-links a{
    color:#9bb6ff;
    text-decoration:none;
    font-weight:500;
}

.extra-links a:hover{
    text-decoration:underline;
}

/* RESPONSIVE */
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