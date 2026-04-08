<?php
session_start();
include("../db.php");

/* DELETE */
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM venues WHERE venue_id=$id");
$_SESSION['success']="Venue deleted!";
header("Location: manage_venues.php");
exit();
}

/* ADD / UPDATE */
if(isset($_POST['save_venue'])){

$event_id=$_POST['event_id'];
$package_id=$_POST['package_id'];
$venue_name=$_POST['venue_name'];
$price=$_POST['price'];

$pkg=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT events.event_name 
FROM packages 
JOIN events ON packages.event_id=events.event_id
WHERE packages.package_id='$package_id'
"));

$folder=strtolower(str_replace(" ","_",$pkg['event_name']));
$upload_path="../uploads/images/venues/".$folder."/";

if(!file_exists($upload_path)){
mkdir($upload_path,0777,true);
}

$image=$_FILES['image']['name'];
$tmp=$_FILES['image']['tmp_name'];

if($image!=""){
move_uploaded_file($tmp,$upload_path.$image);
}

if($_POST['venue_id']!=""){
$id=$_POST['venue_id'];

mysqli_query($conn,"UPDATE venues SET
event_id='$event_id',
package_id='$package_id',
venue_name='$venue_name',
price='$price'
".($image!=""?", venue_image='$image'":"")."
WHERE venue_id=$id");

$_SESSION['success']="Venue updated!";
}else{

mysqli_query($conn,"INSERT INTO venues(event_id,package_id,venue_name,price,venue_image)
VALUES('$event_id','$package_id','$venue_name','$price','$image')");

$_SESSION['success']="Venue added!";
}

header("Location: manage_venues.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Venues</title>

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
.card img{width:100%;height:160px;object-fit:cover;border-radius:12px;margin-bottom:10px;}
.btn{padding:6px 12px;border-radius:8px;color:white;text-decoration:none;font-size:13px;}
.edit{background:#7c3aed;}
.delete{background:#e11d48;}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;}
.modal-content{background:white;padding:30px;border-radius:20px;width:450px;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
.modal-content input,.modal-content select{width:100%;margin-top:10px;padding:10px;border-radius:10px;border:1px solid #ddd;}
.modal-content button{margin-top:15px;padding:12px;border:none;border-radius:25px;background:#7c3aed;color:white;}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<div class="top-bar">
<h2>Manage Venues</h2>
<button class="add-btn" onclick="openAddModal()">+ Add Venue</button>
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

<div class="grid">

<?php
$q=mysqli_query($conn,"
SELECT venues.*,packages.package_name,events.event_name
FROM venues
JOIN packages ON venues.package_id=packages.package_id
JOIN events ON venues.event_id=events.event_id
");

$folders=["annual_day","seminar","sports_day","farewell_party","freshers_party","convocation"];

while($row=mysqli_fetch_assoc($q)){
?>

<div class="card"
data-package="<?php echo $row['package_name']; ?>"
data-event="<?php echo $row['event_name']; ?>">

<?php
$img=$row['venue_image'];
foreach($folders as $folder){
$path="../uploads/images/venues/".$folder."/".$img;
if($img!="" && file_exists($path)){
echo "<img src='$path'>";
break;
}
}
?>

<h3><?php echo $row['venue_name']; ?></h3>
<p><b>Event:</b> <?php echo $row['event_name']; ?></p>
<p><b>Package:</b> <?php echo $row['package_name']; ?></p>
<p><b>₹ <?php echo $row['price']; ?></b></p>

<a href="#" class="btn edit"
onclick="openEditModal('<?php echo $row['venue_id']; ?>','<?php echo $row['event_id']; ?>','<?php echo $row['package_id']; ?>','<?php echo $row['venue_name']; ?>','<?php echo $row['price']; ?>')">Edit</a>

<a href="?delete=<?php echo $row['venue_id']; ?>" class="btn delete">Delete</a>

</div>

<?php } ?>

</div>

</div>

<!-- 🔥 UPDATED MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="venue_id" id="edit_id">

<select name="event_id" id="event_select" onchange="filterPackages()" required>
<option value="">Select Event</option>
<?php
$events=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($events)){
echo "<option value='{$e['event_id']}'>{$e['event_name']}</option>";
}
?>
</select>

<select name="package_id" id="package_select" required>
<option value="">Select Package</option>
<?php
$packages=mysqli_query($conn,"SELECT * FROM packages");
while($p=mysqli_fetch_assoc($packages)){
echo "<option value='{$p['package_id']}' data-event='{$p['event_id']}'>{$p['package_name']}</option>";
}
?>
</select>

<input type="text" name="venue_name" id="edit_name" placeholder="Venue Name">
<input type="number" name="price" id="edit_price" placeholder="Price">
<input type="file" name="image">

<button name="save_venue">Save</button>

</form>

</div>
</div>

<script>
let selectedEvent="all";
let selectedPackage="all";

function filterEvent(){
selectedEvent=document.getElementById("eventFilter").value;
applyFilters();
}

function filterPackage(type,btn){
selectedPackage=type;
document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
btn.classList.add("active");
applyFilters();
}

function applyFilters(){
document.querySelectorAll(".card").forEach(c=>{
let e=(selectedEvent=="all" || c.dataset.event==selectedEvent);
let p=(selectedPackage=="all" || c.dataset.package==selectedPackage);
c.style.display=(e && p)?"block":"none";
});
}

/* 🔥 package filter */
function filterPackages(){
let eventId=document.getElementById("event_select").value;
document.querySelectorAll("#package_select option").forEach(opt=>{
if(opt.value=="" || opt.dataset.event==eventId){
opt.style.display="block";
}else{
opt.style.display="none";
}
});
}

function openAddModal(){
modal.style.display="block";
edit_id.value="";
edit_name.value="";
edit_price.value="";
}

function openEditModal(id,event,pkg,name,price){
modal.style.display="block";
edit_id.value=id;
event_select.value=event;
filterPackages();
package_select.value=pkg;
edit_name.value=name;
edit_price.value=price;
}

window.onclick=function(e){
if(e.target==modal) modal.style.display="none";
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