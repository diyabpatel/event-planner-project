<?php
include("db.php");

$msg = "";

if(isset($_POST['signup'])){
    
    $college_name = $_POST['college_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // MD5 password (old but works)
    $password = md5($_POST['password']);

    // Check connection
    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }

    // Check email exists
    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    
    if(mysqli_num_rows($check) > 0){
        $msg = "<p style='color:red;text-align:center;'>Email already exists!</p>";
    } 
    else {

        $query = "INSERT INTO users 
        (college_name,email,password,phone,address,city,state)
        VALUES
        ('$college_name','$email','$password','$phone','$address','$city','$state')";

        if(mysqli_query($conn,$query)){
            $msg = "<p style='color:green;text-align:center;'>Signup Successful!</p>";
        } else {
            $msg = "<p style='color:red;text-align:center;'>Error: ".mysqli_error($conn)."</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>College Signup</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;margin:0;padding:0;}

body{
font-family:'Poppins',sans-serif;
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#e9e6f7,#dcd6f7);
padding:20px;
}

.signup-card{
width:450px;
max-width:95%;
background:rgba(255,255,255,0.65);
border-radius:22px;
padding:40px 35px;
backdrop-filter:blur(14px);
box-shadow:0 25px 60px rgba(0,0,0,0.25);
border:1px solid rgba(255,255,255,0.4);
}

.signup-card h2{
text-align:center;
margin-bottom:20px;
color:#6a5acd;
}

.field{
margin-bottom:15px;
}

.field input,
.field textarea{
width:100%;
padding:12px;
border:none;
border-radius:12px;
background:#e9edf7;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:30px;
background:#6a5acd;
color:white;
font-size:16px;
cursor:pointer;
margin-top:10px;
}

.signup-card a{
display:block;
text-align:center;
margin-top:15px;
color:#6a5acd;
text-decoration:none;
}

.signup-card a:hover{
text-decoration:underline;
}
</style>

</head>
<body>

<div class="signup-card">

<h2>College Signup</h2>

<!-- MESSAGE -->
<?php echo $msg; ?>

<form method="post">

    <div class="field">
        <input type="text" name="college_name" placeholder="College Name" required>
    </div>

    <div class="field">
        <input type="email" name="email" placeholder="Email" required>
    </div>

    <div class="field">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <div class="field">
        <input type="text" name="phone" placeholder="Phone" required>
    </div>

    <div class="field">
        <textarea name="address" placeholder="Address"></textarea>
    </div>

    <div class="field">
        <input type="text" name="city" placeholder="City">
    </div>

    <div class="field">
        <input type="text" name="state" placeholder="State">
    </div>

    <button type="submit" name="signup">Create Account</button>

</form>

<a href="login.php">Already have an account? Login</a>

</div>

</body>
</html>