<?php
session_start();
include("../db.php");

/* DELETE */
if(isset($_GET['delete'])){
$id=$_GET['delete'];

/* delete image */
$res=mysqli_query($conn,"SELECT seat_images FROM seats WHERE seat_id=$id");
$img=mysqli_fetch_assoc($res);

if($img && $img['seat_images']!=""){
@unlink("../uploads/images/seats/".$img['seat_images']);
}

mysqli_query($conn,"DELETE FROM seats WHERE seat_id=$id");
$_SESSION['success']="Seat deleted!";
header("Location: manage_seats.php");
exit();
}

/* ADD / UPDATE */
if(isset($_POST['save_seat'])){

$event_id=$_POST['event_id'];
$package_id=$_POST['package_id'];
$seat_type=$_POST['seat_type'];
$price=$_POST['price'];

$image="";
if($_FILES['image']['name']!=""){
$image=time()."_".$_FILES['image']['name'];
$path="../uploads/images/seats/";
if(!file_exists($path)){ mkdir($path,0777,true); }
move_uploaded_file($_FILES['image']['tmp_name'],$path.$image);
}

if($_POST['seat_id']!=""){
$id=$_POST['seat_id'];

mysqli_query($conn,"UPDATE seats SET
event_id='$event_id',
package_id='$package_id',
seat_type='$seat_type',
price='$price'
".($image!=""?", seat_images='$image'":"")."
WHERE seat_id=$id");

$_SESSION['success']="Seat updated!";
}else{

mysqli_query($conn,"INSERT INTO seats(event_id,package_id,seat_type,price,seat_images)
VALUES('$event_id','$package_id','$seat_type','$price','$image')");

$_SESSION['success']="Seat added!";
}

header("Location: manage_seats.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Seats</title>

<style>
body{background:linear-gradient(135deg,#f5f3ff,#ede9fe);font-family:'Poppins';}
.main-content{margin-left:260px;padding:30px;}
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
h2{color:#5b21b6;}
.add-btn{padding:10px 20px;border:none;border-radius:25px;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:white;cursor:pointer;}
.filters{display:flex;gap:10px;margin-bottom:20px;}
select.filter{padding:10px;border-radius:12px;border:1px solid #ddd;}
button.filter-btn{padding:8px 15px;border:none;border-radius:20px;background:#ddd;cursor:pointer;}
button.active{background:#7c3aed;color:white;}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;}
.card{background:white;border-radius:18px;padding:20px;box-shadow:0 10px 25px rgba(91,33,182,0.15);}
.card img{width:100%;height:160px;object-fit:cover;border-radius:12px;margin-bottom:10px;}
.btn{padding:6px 12px;border-radius:8px;color:white;text-decoration:none;font-size:13px;}
.edit{background:#7c3aed;}
.delete{background:#e11d48;}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);}
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
<h2>Manage Seats</h2>
<button class="add-btn" onclick="openAddModal()">+ Add Seat</button>
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

<button type="button" class="filter-btn active" onclick="filterPackage('all',this)">All</button>
<button type="button" class="filter-btn" onclick="filterPackage('Basic',this)">Basic</button>
<button type="button" class="filter-btn" onclick="filterPackage('Standard',this)">Standard</button>
<button type="button" class="filter-btn" onclick="filterPackage('Premium',this)">Premium</button>
</div>

<div class="grid">

<?php
$q=mysqli_query($conn,"
SELECT seats.*,packages.package_name,events.event_name
FROM seats
JOIN packages ON seats.package_id=packages.package_id
JOIN events ON seats.event_id=events.event_id
");

while($row=mysqli_fetch_assoc($q)){
?>

<div class="card" data-event="<?php echo $row['event_name']; ?>" data-package="<?php echo $row['package_name']; ?>">

<?php if($row['seat_images']!=""){ ?>
<img src="../uploads/images/seats/<?php echo $row['seat_images']; ?>">
<?php } ?>

<h3><?php echo $row['seat_type']; ?></h3>
<p><b>Event:</b> <?php echo $row['event_name']; ?></p>
<p><b>Package:</b> <?php echo $row['package_name']; ?></p>
<p><b>₹ <?php echo $row['price']; ?></b></p>

<a href="#" class="btn edit"
onclick="openEditModal('<?php echo $row['seat_id']; ?>','<?php echo $row['event_id']; ?>','<?php echo $row['package_id']; ?>','<?php echo $row['seat_type']; ?>','<?php echo $row['price']; ?>')">Edit</a>

<a href="?delete=<?php echo $row['seat_id']; ?>" class="btn delete">Delete</a>

</div>

<?php } ?>

</div>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
<div class="modal-content">

<h3 id="modal_title">Add Seat</h3>

<form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

<input type="hidden" name="seat_id" id="edit_id">
<input type="hidden" name="event_id" id="hidden_event">
<input type="hidden" name="package_id" id="hidden_package">

<select id="event_select" onchange="filterPackages()">
<option value="">Select Event</option>
<?php
$events=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($events)){
echo "<option value='{$e['event_id']}'>{$e['event_name']}</option>";
}
?>
</select>

<select id="package_select">
<option value="">Select Package</option>
<?php
$packages=mysqli_query($conn,"SELECT * FROM packages");
while($p=mysqli_fetch_assoc($packages)){
echo "<option value='{$p['package_id']}' data-event='{$p['event_id']}'>{$p['package_name']}</option>";
}
?>
</select>

<input type="text" name="seat_type" id="edit_name" placeholder="Seat Type">
<input type="number" name="price" id="edit_price" placeholder="Price">
<input type="file" name="image">

<button name="save_seat">Save</button>

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
let e=(selectedEvent=="all"||c.dataset.event==selectedEvent);
let p=(selectedPackage=="all"||c.dataset.package==selectedPackage);
c.style.display=(e&&p)?"block":"none";
});
}

function filterPackages(){
let e=event_select.value;
package_select.value="";
document.querySelectorAll("#package_select option").forEach(o=>{
o.hidden=!(o.value==""||o.dataset.event==e);
});
}

function openAddModal(){
modal.style.display="block";
modal_title.innerText="Add Seat";
edit_id.value="";
edit_name.value="";
edit_price.value="";
event_select.disabled=false;
package_select.disabled=false;
}

function openEditModal(id,event,pkg,name,price){
modal.style.display="block";
modal_title.innerText="Edit Seat";

edit_id.value=id;
event_select.value=event;
filterPackages();
package_select.value=pkg;

edit_name.value=name;
edit_price.value=price;

event_select.disabled=true;
package_select.disabled=true;

hidden_event.value=event;
hidden_package.value=pkg;
}

function validateForm(){
hidden_event.value=event_select.value;
hidden_package.value=package_select.value;

if(hidden_event.value==""){alert("Select Event");return false;}
if(hidden_package.value==""){alert("Select Package");return false;}
return true;
}

window.onclick=function(e){
if(e.target==modal) modal.style.display="none";
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