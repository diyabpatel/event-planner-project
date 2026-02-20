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
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>College Signup</title>

<!-- Stylish but safe font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box}

body{
margin:0;
font-family:'Poppins','Segoe UI',sans-serif;
min-height:100vh;
background:radial-gradient(circle at top,#1e2a5a,#0b1020 70%);
display:flex;
justify-content:center;
align-items:center;
color:white;
}

/* GLASS CARD – SMALLER */
.signup-card{
width:380px;            /* ⬅ smaller */
background:rgba(255,255,255,0.15);
backdrop-filter:blur(16px);
border-radius:18px;
padding:30px 28px;      /* ⬅ compact padding */
box-shadow:0 22px 50px rgba(0,0,0,0.7);
}

/* TITLE */
.signup-card h2{
text-align:center;
margin-bottom:22px;
font-weight:600;
font-size:22px;         /* ⬅ smaller title */
letter-spacing:.5px;
background:linear-gradient(90deg,#9bb6ff,#e0e7ff);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* FIELD */
.field{
margin-bottom:14px;     /* ⬅ tighter spacing */
}

.field input,
.field textarea{
width:100%;
padding:10px;           /* ⬅ smaller input */
border-radius:9px;
border:none;
background:rgba(0,0,0,0.45);
color:white;
font-size:13px;         /* ⬅ compact text */
font-family:'Poppins',sans-serif;
box-shadow:inset 0 0 0 1px rgba(255,255,255,0.2);
}

.field small{
display:block;
margin-top:4px;
font-size:11px;         /* ⬅ subtle helper */
color:#c3d0ff;
opacity:.85;
}

/* FOCUS */
.field input:focus,
.field textarea:focus{
outline:none;
box-shadow:
inset 0 0 0 1px #7aa2ff,
0 0 0 3px rgba(122,162,255,0.22);
}

textarea{
resize:none;
min-height:55px;        /* ⬅ compact textarea */
}

/* BUTTON */
button{
width:100%;
padding:11px;
margin-top:14px;
border:none;
border-radius:26px;
font-size:14px;
font-weight:500;
cursor:pointer;
font-family:'Poppins',sans-serif;
background:linear-gradient(135deg,#7aa2ff,#4f7cff);
color:white;
box-shadow:0 14px 30px rgba(122,162,255,0.6);
transition:.3s;
}

button:hover{
transform:translateY(-1px);
box-shadow:0 18px 38px rgba(122,162,255,0.75);
}

/* LINK */
.signup-card a{
display:block;
text-align:center;
margin-top:14px;
font-size:13px;
color:#9bb6ff;
text-decoration:none;
}

.signup-card a:hover{text-decoration:underline}

/* RESPONSIVE */
@media(max-width:420px){
.signup-card{width:92%;}
}
</style>

</head>
<body>

<div class="signup-card">

<h2>College Signup</h2>

<form method="post">

    <div class="field">
        <input type="text" name="college_name" required>
        <small>Official college name (e.g. ABC Institute)</small>
    </div>

    <div class="field">
        <input type="email" name="email" required>
        <small>College email ID</small>
    </div>

    <div class="field">
        <input type="password" name="password" required>
        <small>Minimum 6 characters</small>
    </div>

    <div class="field">
        <input type="text" name="phone" required>
        <small>Contact number</small>
    </div>

    <div class="field">
        <textarea name="address"></textarea>
        <small>College address (optional)</small>
    </div>

    <div class="field">
        <input type="text" name="city">
        <small>City</small>
    </div>

    <div class="field">
        <input type="text" name="state">
        <small>State</small>
    </div>

    <button type="submit" name="signup">Create Account</button>

</form>

<a href="login.php">Already have an account? Login</a>

</div>

</body>
</html>