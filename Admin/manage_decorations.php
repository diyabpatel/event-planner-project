<?php
session_start();
include("../db.php");

/* DELETE */
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM decorations WHERE decoration_id=$id");
$_SESSION['success']="Decoration deleted!";
header("Location: manage_decorations.php");
exit();
}

/* ADD / UPDATE */
if(isset($_POST['save_decoration'])){

$package_id=$_POST['package_id'];
$decoration_name=$_POST['decoration_name'];
$price=$_POST['price'];

/* EVENT ID */
$pkg=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT event_id FROM packages WHERE package_id='$package_id'
"));
$event_id=$pkg['event_id'];

if($_POST['decoration_id']!=""){
$id=$_POST['decoration_id'];

mysqli_query($conn,"UPDATE decorations SET 
event_id='$event_id',
package_id='$package_id',
decoration_name='$decoration_name',
price='$price'
WHERE decoration_id=$id");

$_SESSION['success']="Decoration updated!";
}else{

mysqli_query($conn,"INSERT INTO decorations(event_id,package_id,decoration_name,price)
VALUES('$event_id','$package_id','$decoration_name','$price')");

$_SESSION['success']="Decoration added!";
}

header("Location: manage_decorations.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Decorations</title>

<style>
body{background:linear-gradient(135deg,#f5f3ff,#ede9fe);font-family:'Poppins';}
.main-content{margin-left:260px;padding:30px;}

.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
h2{color:#5b21b6;}

.add-btn{
padding:10px 20px;
border:none;
border-radius:25px;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
cursor:pointer;
}

/* FILTER */
.filters{display:flex;gap:10px;margin-bottom:20px;}
select.filter{padding:10px;border-radius:12px;border:1px solid #ddd;}
button.filter-btn{padding:8px 15px;border:none;border-radius:20px;background:#ddd;cursor:pointer;}
button.active{background:#7c3aed;color:white;}

/* GRID */
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;}

.card{
background:white;
border-radius:18px;
padding:20px;
box-shadow:0 10px 25px rgba(91,33,182,0.15);
}

.card h3{color:#5b21b6;}

.btn{
padding:6px 12px;
border-radius:8px;
color:white;
text-decoration:none;
font-size:13px;
}

.edit{background:#7c3aed;}
.delete{background:#e11d48;}

.event-title{
margin:25px 0 10px;
color:#4c1d95;
font-weight:600;
}

/* MODAL */
.modal{
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
z-index:9999;
}

.modal-content{
background:white;
padding:30px;
border-radius:20px;
width:400px;
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
}

.modal-content input,.modal-content select{
width:100%;
margin-top:10px;
padding:10px;
border-radius:10px;
border:1px solid #ddd;
}

.modal-content button{
margin-top:15px;
padding:12px;
border:none;
border-radius:25px;
background:#7c3aed;
color:white;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<div class="top-bar">
<h2>Manage Decorations</h2>
<button class="add-btn" onclick="openAddModal()">+ Add Decoration</button>
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
SELECT decorations.*,packages.package_name
FROM decorations
JOIN packages ON decorations.package_id=packages.package_id
WHERE decorations.event_id=".$e['event_id']."
");

while($row=mysqli_fetch_assoc($q)){
?>

<div class="card" data-package="<?php echo $row['package_name']; ?>">

<h3><?php echo $row['decoration_name']; ?></h3>
<p><b>Package:</b> <?php echo $row['package_name']; ?></p>
<p><b>₹ <?php echo $row['price']; ?></b></p>

<a href="#" class="btn edit"
onclick="openEditModal(
'<?php echo $row['decoration_id']; ?>',
'<?php echo $row['package_id']; ?>',
'<?php echo $row['decoration_name']; ?>',
'<?php echo $row['price']; ?>'
)">Edit</a>

<a href="?delete=<?php echo $row['decoration_id']; ?>" class="btn delete">Delete</a>

</div>

<?php } ?>

</div>
</div>

<?php } ?>

</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal">
<div class="modal-content">
<h3>Add Decoration</h3>

<form method="post">

<select name="package_id" required>
<?php
$p=mysqli_query($conn,"
SELECT packages.*, events.event_name 
FROM packages 
JOIN events ON packages.event_id=events.event_id
");
while($pkg=mysqli_fetch_assoc($p)){
echo "<option value='{$pkg['package_id']}'>
{$pkg['event_name']} - {$pkg['package_name']}
</option>";
}
?>
</select>

<input type="text" name="decoration_name" placeholder="Decoration Name">
<input type="number" name="price" placeholder="Price">

<button name="save_decoration">Add</button>
</form>

</div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
<div class="modal-content">
<h3>Edit Decoration</h3>

<form method="post">

<input type="hidden" name="decoration_id" id="edit_id">

<select id="edit_package_display" disabled>
<?php
$p=mysqli_query($conn,"
SELECT packages.*, events.event_name 
FROM packages 
JOIN events ON packages.event_id=events.event_id
");
while($pkg=mysqli_fetch_assoc($p)){
echo "<option value='{$pkg['package_id']}'>
{$pkg['event_name']} - {$pkg['package_name']}
</option>";
}
?>
</select>

<input type="hidden" name="package_id" id="edit_package">

<input type="text" name="decoration_name" id="edit_name">
<input type="number" name="price" id="edit_price">

<button name="save_decoration">Update</button>

</form>
</div>
</div>

<script>
function openAddModal(){
document.getElementById("addModal").style.display="block";
}

function openEditModal(id,pkg,name,price){
document.getElementById("editModal").style.display="block";

document.getElementById("edit_id").value=id;

/* set both */
document.getElementById("edit_package").value=pkg;
document.getElementById("edit_package_display").value=pkg;

document.getElementById("edit_name").value=name;
document.getElementById("edit_price").value=price;
}

window.onclick=function(e){
if(e.target==addModal) addModal.style.display="none";
if(e.target==editModal) editModal.style.display="none";
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
confetti({particleCount:120,spread:100});
</script>
<?php unset($_SESSION['success']); } ?>

</body>
</html>