<?php
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

header("Location: manage_events.php");
exit();
}

/* FETCH FOR EDIT */
$edit = null;

if(isset($_GET['edit'])){
$id = $_GET['edit'];
$res = mysqli_query($conn,"SELECT * FROM events WHERE event_id=$id");
$edit = mysqli_fetch_assoc($res);
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

}else{

mysqli_query($conn,"INSERT INTO events(event_name,description,image)
VALUES('$event_name','$description','$image_name')");
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
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
font-family:'Poppins',sans-serif;
}

.main-content{
margin-left:260px;
padding:30px;
}

h2{
text-align:center;
color:#5b21b6;
margin-bottom:30px;
}

/* CARD GRID */
.event-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
}

/* CARD */
.event-card{
background:white;
border-radius:18px;
overflow:hidden;
box-shadow:0 10px 30px rgba(91,33,182,0.2);
transition:0.3s;
}

.event-card:hover{
transform:translateY(-6px);
}

/* IMAGE */
.event-card img{
width:100%;
height:180px;
object-fit:cover;
}

/* BODY */
.event-body{
padding:15px;
}

.event-body h3{
color:#5b21b6;
margin-bottom:8px;
}

.event-body p{
font-size:13px;
color:#555;
height:40px;
overflow:hidden;
}

/* STATS */
.stats{
display:flex;
justify-content:space-between;
margin-top:10px;
font-size:13px;
color:#6d28d9;
}

/* BUTTONS */
.actions{
display:flex;
gap:10px;
margin-top:15px;
}

.btn{
flex:1;
padding:8px;
text-align:center;
border-radius:8px;
color:white;
text-decoration:none;
font-size:13px;
}

.view{
background:#6366f1;
}

.edit{
background:#7c3aed;
}

.delete{
background:#e11d48;
}
.modal{
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
z-index:1000;
}

/* CENTER PERFECTLY */
.modal-content{
background:white;
padding:30px;
border-radius:20px;
width:500px;
max-width:90%;
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
box-shadow:0 20px 50px rgba(0,0,0,0.3);
animation:fadeIn 0.3s ease;
}

/* CLOSE BUTTON */
.close{
position:absolute;
top:12px;
right:18px;
font-size:22px;
cursor:pointer;
color:#333;
}

/* TITLE */
.modal-content h3{
margin-bottom:20px;
color:#5b21b6;
text-align:center;
}

/* FORM LAYOUT FIX */
.modal-content form{
display:flex;
flex-direction:column;
gap:15px;
}

/* LABEL */
.modal-content label{
font-weight:600;
color:#4c1d95;
font-size:14px;
}

/* INPUT + TEXTAREA */
.modal-content input,
.modal-content textarea{
width:100%;
padding:10px;
border-radius:10px;
border:1px solid #ddd;
outline:none;
font-size:14px;
}

/* TEXTAREA FIX */
.modal-content textarea{
resize:none;
height:80px;
}

/* BUTTON */
.modal-content button{
margin-top:10px;
padding:12px;
border:none;
border-radius:25px;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

.modal-content button:hover{
transform:scale(1.05);
box-shadow:0 10px 25px rgba(124,58,237,0.4);
}

/* ANIMATION */
@keyframes fadeIn{
from{opacity:0; transform:translate(-50%,-60%) scale(0.9);}
to{opacity:1; transform:translate(-50%,-50%) scale(1);}
}
</style>
</head>

<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<h2>Manage Events</h2>

<div class="event-grid">

<?php
$q = mysqli_query($conn,"SELECT * FROM events");

while($row = mysqli_fetch_assoc($q)){

$event_id = $row['event_id'];

/* BOOKINGS COUNT */
$b = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings WHERE event_id=$event_id"));

/* TOTAL REVENUE */
$r = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) as revenue FROM bookings WHERE event_id=$event_id"));

?>

<div class="event-card">

<?php if($row['image']!=""){ ?>
<img src="../uploads/images/events_images/<?php echo $row['image']; ?>">
<?php } ?>

<div class="event-body">

<h3><?php echo $row['event_name']; ?></h3>

<p><?php echo $row['description']; ?></p>



<div class="actions">

<a href="event_insights.php?event_id=<?php echo $event_id; ?>" class="btn view">
View Insights
</a>

<a href="#" class="btn edit"
onclick="openEditModal(
'<?php echo $row['event_id']; ?>',
'<?php echo addslashes($row['event_name']); ?>',
'<?php echo addslashes($row['description']); ?>'
)">
Edit
</a>

<a href="manage_events.php?delete=<?php echo $event_id; ?>" class="btn delete"
onclick="return confirm('Delete this event?');">
Delete
</a>

</div>

</div>
</div>

<?php } ?>

</div>

</div>
<div id="editModal" class="modal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h3>Edit Event</h3>

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="event_id" id="edit_id">

<label>Event Name</label>
<input type="text" name="event_name" id="edit_name" required>

<label>Description</label>
<textarea name="description" id="edit_desc"></textarea>

<label>Event Image</label>
<input type="file" name="image">

<button type="submit" name="save">Update Event</button>

</form>

</div>
</div>
</body>
<script>
function openEditModal(id, name, desc){

document.getElementById("editModal").style.display = "block";

document.getElementById("edit_id").value = id;
document.getElementById("edit_name").value = name;
document.getElementById("edit_desc").value = desc;
}

function closeModal(){
document.getElementById("editModal").style.display = "none";
}

/* CLOSE ON OUTSIDE CLICK */
window.onclick = function(e){
let modal = document.getElementById("editModal");
if(e.target == modal){
    modal.style.display = "none";
}
}
</script>
</html>