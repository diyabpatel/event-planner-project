<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../login.php");
    exit();
}

include("../db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_GET['event_id']))
{
    echo "Invalid Event";
    exit();
}

$event_id = intval($_GET['event_id']);

$event_query = "SELECT * FROM events WHERE event_id=$event_id";
$event_result = mysqli_query($conn,$event_query);
$event = mysqli_fetch_assoc($event_result);

$package_query = "SELECT * FROM packages WHERE event_id=$event_id";
$packages = mysqli_query($conn,$package_query);

if(isset($_POST['book']))
{

$package_id = $_POST['package_id'];
$venue_id = $_POST['venue_id'];
$decoration_id = $_POST['decoration_id'];
$seat_id = $_POST['seat_id'];
$food_id = $_POST['food_id'];
$coverage_id = $_POST['coverage_id'];
$event_date = $_POST['event_date'];

$total = 0;

$q = mysqli_query($conn,"SELECT price FROM venues WHERE venue_id=$venue_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM decorations WHERE decoration_id=$decoration_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM seats WHERE seat_id=$seat_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM food WHERE food_id=$food_id");
$total += mysqli_fetch_assoc($q)['price'];

$q = mysqli_query($conn,"SELECT price FROM coverage WHERE coverage_id=$coverage_id");
$total += mysqli_fetch_assoc($q)['price'];

$query = "INSERT INTO bookings
(user_id,event_id,package_id,event_date,total_price)
VALUES
('$user_id','$event_id','$package_id','$event_date','$total')";

mysqli_query($conn,$query);

echo "<script>alert('Booking Successful! Total: ₹$total');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Book Event</title>

<style>

body{
    margin:0;
    padding:0;
    height:100vh;
    font-family: 'Poppins', sans-serif;
    background: url('/event-planner-project/uploads/images/annual/stage_bg.jpg') no-repeat center center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
}

.container{
    width:420px;
    padding:28px 30px;
    border-radius:22px;
    background: rgba(20,20,40,0.75);
    backdrop-filter: blur(20px);
    box-shadow: 0 0 50px rgba(0,255,255,0.35);
    color:white;
}

h2{
    font-size:20px;
    text-align:center;
    margin-bottom:4px;
}

.subtitle{
    font-size:12px;
    text-align:center;
    margin-bottom:15px;
    opacity:0.8;
}

label{
    font-size:12px;
    margin-bottom:3px;
    display:block;
}

select,input{
    width:100%;
    padding:8px 12px;
    border-radius:10px;
    border:none;
    outline:none;
    margin-bottom:10px;
    background: rgba(255,255,255,0.15);
    color:white;
    font-size:13px;
}

select option{
    background:#1b1b2f;
    color:white;
}

#totalBox{
    margin:10px 0;
    padding:10px;
    border-radius:10px;
    background: rgba(0,0,0,0.5);
    text-align:center;
    font-weight:600;
    font-size:14px;
}

button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:12px;
    background: linear-gradient(90deg,#6dd5ed,#2193b0);
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.04);
    box-shadow:0 0 20px #6dd5ed;
}

</style>

</head>

<body>

<div class="container">

<h2>🎉 Book Event: <?php echo $event['event_name']; ?></h2>
<div class="subtitle">Select your package and confirm booking</div>

<form method="POST">

<label>Select Package</label>
<select name="package_id" required onchange="this.form.submit()">
<option value="">Select Package</option>

<?php
while($row=mysqli_fetch_assoc($packages))
{
$selected = (isset($_POST['package_id']) && $_POST['package_id']==$row['package_id']) ? "selected" : "";
echo "<option value='".$row['package_id']."' $selected>".$row['package_name']."</option>";
}
?>
</select>

<?php
if(isset($_POST['package_id']))
{
$package_id = $_POST['package_id'];
?>

<label>Select Venue</label>
<select name="venue_id" required>
<?php
$q=mysqli_query($conn,"SELECT * FROM venues WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['venue_id']."'>".$row['venue_name']." (₹".$row['price'].")</option>";
}
?>
</select>

<label>Select Decoration</label>
<select name="decoration_id" required>
<?php
$q=mysqli_query($conn,"SELECT * FROM decorations WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['decoration_id']."'>".$row['decoration_name']." (₹".$row['price'].")</option>";
}
?>
</select>

<label>Select Seat Type</label>
<select name="seat_id" required>
<?php
$q=mysqli_query($conn,"SELECT * FROM seats WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['seat_id']."'>".$row['seat_type']." (₹".$row['price'].")</option>";
}
?>
</select>

<label>Select Food</label>
<select name="food_id" required>
<?php
$q=mysqli_query($conn,"SELECT * FROM food WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['food_id']."'>".$row['menu']." (₹".$row['price'].")</option>";
}
?>
</select>

<label>Select Coverage</label>
<select name="coverage_id" required>
<?php
$q=mysqli_query($conn,"SELECT * FROM coverage WHERE package_id=$package_id");
while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['coverage_id']."'>".$row['coverage_type']." (₹".$row['price'].")</option>";
}
?>
</select>

<label>Select Event Date</label>
<input type="date" name="event_date" required>

<div id="totalBox">
Total Price: ₹ <span id="totalAmount">0</span>
</div>

<button type="submit" name="book">
Book Now
</button>

<?php } ?>

</form>

</div>

<script>

function calculateTotal(){
    let total = 0;

    document.querySelectorAll("select").forEach(select => {
        let text = select.options[select.selectedIndex]?.text;
        if(text){
            let match = text.match(/₹(\d+)/);
            if(match){
                total += parseInt(match[1]);
            }
        }
    });

    document.getElementById("totalAmount").innerText = total;
}

document.querySelectorAll("select").forEach(select => {
    select.addEventListener("change", calculateTotal);
});

calculateTotal();

</script>

</body>
</html>
