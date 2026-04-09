<?php
session_start();
include("../db.php");

/* DELETE */
if(isset($_GET['delete'])){
$id = $_GET['delete'];
mysqli_query($conn,"DELETE FROM food WHERE food_id=$id");
$_SESSION['success']="Food deleted!";
header("Location: manage_food.php");
exit();
}

/* ADD / UPDATE */
if(isset($_POST['save_food'])){

$food_type=$_POST['food_type'];
$menu=$_POST['menu'];
$price=$_POST['price'];

/* ================= UPDATE ================= */
if(isset($_POST['food_id']) && $_POST['food_id']!=""){

$id=$_POST['food_id'];

/* ⚠️ OLD EVENT & PACKAGE SAME RAKHVANA */
$old=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT event_id,package_id FROM food WHERE food_id='$id'
"));

$event_id=$old['event_id'];
$package_id=$old['package_id'];

mysqli_query($conn,"UPDATE food SET 
food_type='$food_type',
menu='$menu',
price='$price'
WHERE food_id=$id");

$_SESSION['success']="Food updated!";
}

/* ================= ADD ================= */
else{

$event_name=$_POST['event_name'];
$package_name=$_POST['package_id'];

/* EVENT */
$ev=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT event_id FROM events WHERE event_name='$event_name'
"));
$event_id=$ev['event_id'];

/* PACKAGE */
$pkg=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM packages 
WHERE package_name='$package_name' 
AND event_id='$event_id'
LIMIT 1
"));
$package_id=$pkg['package_id'];

mysqli_query($conn,"INSERT INTO food(event_id,package_id,food_type,menu,price)
VALUES('$event_id','$package_id','$food_type','$menu','$price')");

$_SESSION['success']="Food added!";
}

header("Location: manage_food.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Food</title>

<style>
body{background:linear-gradient(135deg,#f5f3ff,#ede9fe);font-family:'Poppins';}
.main-content{margin-left:260px;padding:30px;}
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
h2{color:#5b21b6;}
.add-btn{padding:10px 20px;border:none;border-radius:25px;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:white;cursor:pointer;}
.filters{display:flex;gap:10px;margin-bottom:20px;}
select.filter{padding:10px 15px;border-radius:12px;border:1px solid #ddd;}
button.filter-btn{padding:8px 15px;border:none;border-radius:20px;background:#ddd;cursor:pointer;}
button.active{background:#7c3aed;color:white;}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;}
.card{background:white;border-radius:18px;padding:20px;box-shadow:0 10px 25px rgba(91,33,182,0.15);}
.card h3{color:#5b21b6;}
.btn{padding:6px 12px;border-radius:8px;color:white;text-decoration:none;font-size:13px;}
.edit{background:#7c3aed;}
.delete{background:#e11d48;}
.event-title{margin:25px 0 10px;color:#4c1d95;font-weight:600;}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;}
.modal-content{background:white;padding:30px;border-radius:20px;width:450px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
.modal-content input,.modal-content textarea,.modal-content select{width:100%;margin-top:10px;padding:10px;border-radius:10px;border:1px solid #ddd;}
.modal-content button{margin-top:15px;padding:12px;border:none;border-radius:25px;background:#7c3aed;color:white;}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<div class="top-bar">
<h2>Manage Food</h2>
<button class="add-btn" onclick="openAddModal()">+ Add Food</button>
</div>

<div class="filters">
<select class="filter" id="eventFilter" onchange="filterEvent()">
<option value="all">All Events</option>
<?php
$ev=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($ev)){
echo "<option value='{$e['event_name']}'>{$e['event_name']}</option>";
}
?>
</select>

<button class="filter-btn active" onclick="filterPackage('all',this)">All</button>
<button class="filter-btn" onclick="filterPackage('Basic',this)">Basic</button>
<button class="filter-btn" onclick="filterPackage('Standard',this)">Standard</button>
<button class="filter-btn" onclick="filterPackage('Premium',this)">Premium</button>
</div>

<?php
$events=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($events)){
?>

<div class="event-section">
<div class="event-title"><?php echo $e['event_name']; ?></div>

<div class="grid">

<?php
$q=mysqli_query($conn,"
SELECT food.*,packages.package_name
FROM food
JOIN packages ON food.package_id=packages.package_id
WHERE food.event_id=".$e['event_id']."
");

while($row=mysqli_fetch_assoc($q)){
?>

<div class="card" data-package="<?php echo $row['package_name']; ?>">
<h3><?php echo $row['food_type']; ?></h3>
<p><b>Package:</b> <?php echo $row['package_name']; ?></p>
<p><?php echo $row['menu']; ?></p>
<p><b>₹ <?php echo $row['price']; ?></b></p>

<a href="#" class="btn edit"
onclick="openEditModal(
'<?php echo $row['food_id']; ?>',
'<?php echo $e['event_name']; ?>',
'<?php echo $row['package_name']; ?>',
'<?php echo $row['food_type']; ?>',
'<?php echo addslashes($row['menu']); ?>',
'<?php echo $row['price']; ?>'
)">Edit</a>

<a href="?delete=<?php echo $row['food_id']; ?>" class="btn delete">Delete</a>
</div>

<?php } ?>

</div>
</div>

<?php } ?>

</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal">
<div class="modal-content">
<h3>Add Food</h3>

<form method="post">

<select name="event_name" required>
<option value="">Select Event</option>
<?php
$events=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($events)){
echo "<option value='{$e['event_name']}'>{$e['event_name']}</option>";
}
?>
</select>

<select name="package_id">
<?php
$packages=mysqli_query($conn,"SELECT DISTINCT package_name FROM packages");
while($p=mysqli_fetch_assoc($packages)){
echo "<option value='{$p['package_name']}'>{$p['package_name']}</option>";
}
?>
</select>

<select name="food_type">
<option>Breakfast</option>
<option>Lunch</option>
<option>Dinner</option>
<option>Snacks</option>
</select>

<textarea name="menu" placeholder="Description"></textarea>
<input type="number" name="price" placeholder="Price">

<button name="save_food">Add Food</button>
</form>
</div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
<div class="modal-content">
<h3>Edit Food</h3>

<form method="post">

<input type="hidden" name="food_id" id="edit_id">

<!-- 👇 PACKAGE (READ ONLY) -->
<input type="text" id="edit_package_display" disabled style="background:#eee;">

<!-- ✅ ONLY NAME -->
<input type="text" id="edit_type_display" disabled style="background:#eee;">
<input type="hidden" name="food_type" id="edit_type">

<!-- ✅ DESCRIPTION -->
<textarea name="menu" id="edit_menu" placeholder="Description"></textarea>

<!-- ✅ PRICE -->
<input type="number" name="price" id="edit_price" placeholder="Price">

<button name="save_food">Update</button>

</form>
</div>
</div>

<script>
function openAddModal(){
document.getElementById("addModal").style.display="block";
}

function openEditModal(id,event,pkg,type,menu,price){
document.getElementById("editModal").style.display="block";

document.getElementById("edit_id").value=id;

/* 👇 DISPLAY */
document.getElementById("edit_type_display").value=type;

/* 👇 HIDDEN (for DB update) */
document.getElementById("edit_type").value=type;

document.getElementById("edit_menu").value=menu;
document.getElementById("edit_price").value=price;
document.getElementById("edit_package_display").value="Package: "+pkg;
}

window.onclick=function(e){
if(e.target==document.getElementById("addModal")) addModal.style.display="none";
if(e.target==document.getElementById("editModal")) editModal.style.display="none";
}

function filterEvent(){
let val=document.getElementById("eventFilter").value;
document.querySelectorAll(".event-section").forEach(sec=>{
let title=sec.querySelector(".event-title").innerText;
sec.style.display=(val=="all" || title==val)?"block":"none";
});
}

function filterPackage(type,btn){
document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
btn.classList.add("active");

document.querySelectorAll(".card").forEach(c=>{
c.style.display=(type=="all" || c.dataset.package==type)?"block":"none";
});
}
</script>

<?php if(isset($_SESSION['success'])){ ?>
<script>
Swal.fire({title:'Success 🎉',text:'<?php echo $_SESSION['success']; ?>',icon:'success',timer:2000,showConfirmButton:false});
confetti({particleCount:120,spread:100,origin:{x:0.5,y:0.5}});
</script>
<?php unset($_SESSION['success']); } ?>

</body>
</html>