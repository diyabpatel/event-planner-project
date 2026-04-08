<?php
session_start();
include("../db.php");

/* DELETE EVENT */
if(isset($_GET['delete'])){
$id = $_GET['delete'];

$res = mysqli_query($conn,"SELECT image FROM events WHERE event_id=$id");
$row = mysqli_fetch_assoc($res);

if($row && $row['image']!=""){
$path = "../uploads/images/events_images/".$row['image'];
if(file_exists($path)){
unlink($path);
}
}

mysqli_query($conn,"DELETE FROM events WHERE event_id=$id");

$_SESSION['success'] = "Event deleted successfully!";
header("Location: manage_events.php");
exit();
}

/* ADD / UPDATE */
if(isset($_POST['save'])){

$event_name = $_POST['event_name'];
$description = $_POST['description'];

$image_name = "";

if($_FILES['image']['name']!=""){
$tmp = $_FILES['image']['tmp_name'];
$image_name = time()."_".$_FILES['image']['name'];

$folder = "../uploads/images/events_images/";
if(!file_exists($folder)){
mkdir($folder,0777,true);
}

move_uploaded_file($tmp,$folder.$image_name);
}

if($_POST['event_id']!=""){
$id = $_POST['event_id'];

if($image_name!=""){

$res = mysqli_query($conn,"SELECT image FROM events WHERE event_id=$id");
$row = mysqli_fetch_assoc($res);

if($row && $row['image']!=""){
$old = "../uploads/images/events_images/".$row['image'];
if(file_exists($old)){
unlink($old);
}
}

mysqli_query($conn,"UPDATE events SET
event_name='$event_name',
description='$description',
image='$image_name'
WHERE event_id=$id");

}else{

mysqli_query($conn,"UPDATE events SET
event_name='$event_name',
description='$description'
WHERE event_id=$id");
}

$_SESSION['success'] = "Event updated successfully!";

}else{

mysqli_query($conn,"INSERT INTO events(event_name,description,image)
VALUES('$event_name','$description','$image_name')");

$_SESSION['success'] = "Event added successfully!";
}

header("Location: manage_events.php");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Events</title>

<style>
/* SAME CSS */
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
font-family:'Poppins',sans-serif;
}
.main-content{margin-left:260px;padding:30px;}
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;}
h2{color:#5b21b6;margin:0;}
.add-btn{padding:10px 20px;border:none;border-radius:25px;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:white;cursor:pointer;font-weight:600;}
.event-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:25px;}
.event-card{background:white;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(91,33,182,0.2);transition:0.3s;display:flex;flex-direction:column;}
.event-card:hover{transform:translateY(-6px);}
.event-card img{width:100%;height:200px;object-fit:cover;}
.event-body{padding:15px;display:flex;flex-direction:column;flex:1;}
.event-body h3{color:#5b21b6;margin-bottom:8px;}
.event-body p{font-size:14px;color:#555;line-height:1.5;margin-bottom:10px;}
.actions{display:flex;gap:10px;margin-top:auto;}
.btn{flex:1;padding:8px;text-align:center;border-radius:8px;color:white;text-decoration:none;font-size:13px;}
.view{background:#6366f1;}
.edit{background:#7c3aed;}
.delete{background:#e11d48;}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:1000;}
.modal-content{background:white;padding:30px;border-radius:20px;width:450px;max-width:90%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
.close{position:absolute;top:10px;right:15px;font-size:20px;cursor:pointer;}
.modal-content form{display:flex;flex-direction:column;gap:12px;}
.modal-content input,.modal-content textarea{padding:10px;border-radius:8px;border:1px solid #ccc;}
.modal-content button{padding:10px;border:none;border-radius:20px;background:#7c3aed;color:white;cursor:pointer;}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<div class="top-bar">
<h2>Manage Events</h2>
<button class="add-btn" onclick="openAddModal()">+ Add Event</button>
</div>

<div class="event-grid">

<?php
$q = mysqli_query($conn,"SELECT * FROM events");

while($row = mysqli_fetch_assoc($q)){
?>

<div class="event-card">

<?php if($row['image']!=""){ ?>
<img src="../uploads/images/events_images/<?php echo $row['image']; ?>">
<?php } ?>

<div class="event-body">

<h3><?php echo $row['event_name']; ?></h3>
<p><?php echo $row['description']; ?></p>

<div class="actions">

<a href="event_insights.php?event_id=<?php echo $row['event_id']; ?>" class="btn view">View Insights</a>

<a href="#" class="btn edit"
onclick="openEditModal('<?php echo $row['event_id']; ?>','<?php echo addslashes($row['event_name']); ?>','<?php echo addslashes($row['description']); ?>')">
Edit
</a>

<a href="?delete=<?php echo $row['event_id']; ?>" class="btn delete" onclick="return confirm('Delete this event?')">Delete</a>

</div>

</div>
</div>

<?php } ?>

</div>
</div>

<!-- MODALS SAME -->
<!-- ADD MODAL -->
<div id="addModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeAddModal()">&times;</span>
<h3>Add Event</h3>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="event_id" value="">
<input type="text" name="event_name" placeholder="Event Name" required>
<textarea name="description" placeholder="Description"></textarea>
<input type="file" name="image">
<button type="submit" name="save">Add Event</button>
</form>

</div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeEditModal()">&times;</span>
<h3>Edit Event</h3>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="event_id" id="edit_id">
<input type="text" name="event_name" id="edit_name" required>
<textarea name="description" id="edit_desc"></textarea>
<input type="file" name="image">
<button type="submit" name="save">Update Event</button>
</form>

</div>
</div>

<script>
function openAddModal(){document.getElementById("addModal").style.display="block";}
function closeAddModal(){document.getElementById("addModal").style.display="none";}
function openEditModal(id,name,desc){
document.getElementById("editModal").style.display="block";
document.getElementById("edit_id").value=id;
document.getElementById("edit_name").value=name;
document.getElementById("edit_desc").value=desc;
}
function closeEditModal(){document.getElementById("editModal").style.display="none";}
window.onclick=function(e){
if(e.target==document.getElementById("addModal")) closeAddModal();
if(e.target==document.getElementById("editModal")) closeEditModal();
}
</script>

<?php if(isset($_SESSION['success'])){ ?>
<script>
Swal.fire({
title:'Success 🎉',
text:'<?php echo $_SESSION['success']; ?>',
icon:'success',
confirmButtonColor:'#7c3aed',
timer:2000,
showConfirmButton:false
});

// 💥 CENTER CONFETTI BLAST
var duration = 2 * 1000;
var end = Date.now() + duration;

(function frame() {
confetti({
    particleCount: 10,
    spread: 120,
    startVelocity: 45,
    origin: { x: 0.5, y: 0.5 }
});

if (Date.now() < end) requestAnimationFrame(frame);
})();
</script>
<?php unset($_SESSION['success']); } ?>

</body>
</html>