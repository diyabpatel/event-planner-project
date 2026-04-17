<?php
include("db.php");

$msg = "";

if(isset($_POST['signup'])){

    $college_name = trim($_POST['college_name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    /* 🔐 VALIDATIONS */

    // Empty check
    if(empty($college_name) || empty($email) || empty($password_raw) || empty($phone)){
        $msg = "<p class='error'>All required fields must be filled!</p>";
    }

    // Email format
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $msg = "<p class='error'>Invalid email format!</p>";
    }

    // Password length
    elseif(strlen($password_raw) < 6){
        $msg = "<p class='error'>Password must be at least 6 characters!</p>";
    }

    // Phone validation (10 digits)
    elseif(!preg_match("/^[0-9]{10}$/", $phone)){
        $msg = "<p class='error'>Phone must be 10 digits!</p>";
    }

    else{

        $password = md5($password_raw);

        // Email exists check
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
        
        if(mysqli_num_rows($check) > 0){
            $msg = "<p class='error'>Email already exists!</p>";
        } 
        else {

            $query = "INSERT INTO users 
            (college_name,email,password,phone,address,city,state)
            VALUES
            ('$college_name','$email','$password','$phone','$address','$city','$state')";

            if(mysqli_query($conn,$query)){
                $msg = "<p class='success'>Signup Successful!</p>";
            } else {
                $msg = "<p class='error'>Error: ".mysqli_error($conn)."</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>College Signup</title>

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
    padding:20px;
    background:
    radial-gradient(circle at top,#ffffff,#f3f0ff 40%,#e4ddff 70%,#d6ccff);
}

/* 💎 CARD */
.signup-card{
    width:400px;
    background:rgba(255,255,255,0.7);
    backdrop-filter:blur(20px);
    border-radius:22px;
    padding:38px 32px;
    box-shadow:
    0 25px 60px rgba(111,66,193,0.25),
    0 0 25px rgba(140,90,255,0.3),
    inset 0 0 0 1px rgba(255,255,255,0.8);
    position:relative;
    overflow:hidden;
}

/* BORDER */
.signup-card::before{
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
    pointer-events:none;
}

/* TITLE */
.signup-card h2{
    text-align:center;
    margin-bottom:28px;
    font-size:22px;
    background:linear-gradient(90deg,#6f42c1,#9d7bff);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* GRID */
form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

.full{
    grid-column:1 / -1;
}

/* INPUT GROUP */
.form-group{
    display:flex;
    flex-direction:column;
}

/* LABEL */
.form-group label{
    font-size:13px;
    color:#5a4a9c;
    margin-bottom:6px;
}

/* INPUT FIX 🔥 */
.form-group input,
.form-group textarea{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:none;
    outline:none;

    background:#ffffff;

    box-shadow:
    0 5px 15px rgba(0,0,0,0.08),
    inset 0 0 0 1px #e0d8ff;

    transition:0.3s;
}

/* REMOVE DEFAULT BROWSER STYLE */
input, textarea{
    appearance:none;
    -webkit-appearance:none;
}

/* TEXTAREA */
.form-group textarea{
    height:60px;
    resize:none;
}

/* FOCUS */
.form-group input:focus,
.form-group textarea:focus{
    box-shadow:
    0 0 0 2px #a084ff,
    0 0 15px rgba(160,132,255,0.4);
}

/* BUTTON */
.signup-btn{
    grid-column:1 / -1;
    padding:13px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#7f5cff,#6f42c1);
    color:white;
    font-size:14px;
    cursor:pointer;
    margin-top:10px;

    box-shadow:
    0 12px 30px rgba(111,66,193,0.5),
    0 0 20px rgba(140,90,255,0.6);

    transition:0.3s;
}

.signup-btn:hover{
    transform:translateY(-2px);
}

/* LINKS */
.extra-links{
    margin-top:18px;
    text-align:center;
    font-size:13px;
}

.extra-links a{
    color:#7f5cff;
    text-decoration:none;
}

.extra-links a:hover{
    text-decoration:underline;
}

/* MSG */
.success{
    text-align:center;
    color:green;
    margin-bottom:10px;
}

.error{
    text-align:center;
    color:red;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="signup-card">

<h2>Create Account</h2>

<?php echo $msg; ?>

<form method="post">


    <div class="form-group full">
        <label>College Name * </label>
        <input type="text" name="college_name" required>
    </div>

    <div class="form-group">
        <label>Email * </label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Password * </label>
       <input type="password" name="password" required minlength="6">
    </div>

    <div class="form-group">
        <label>Phone * </label>
        <input type="text" name="phone" required pattern="[0-9]{10}" maxlength="10">
    </div>

    <div class="form-group">
        <label>City</label>
        <input type="text" name="city" required>
    </div>

    <div class="form-group full">
        <label>Address</label>
        <textarea name="address" required></textarea>
    </div>

    <div class="form-group">
        <label>State</label>
        <input type="text" name="state" required>
    </div>

    <div></div>

    <button type="submit" name="signup" class="signup-btn">
        Create Account
    </button>

</form>

<div class="extra-links">
    Already have an account?
    <a href="login.php">Login</a>
</div>

</div>

</body>
</html>