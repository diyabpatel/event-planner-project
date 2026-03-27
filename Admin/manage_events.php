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


/* ADD / UPDATE EVENT */

if(isset($_POST['save'])){

$event_name = $_POST['event_name'];
$description = $_POST['description'];

$image_name = "";

/* IMAGE UPLOAD */

if($_FILES['image']['name']!=""){

$tmp = $_FILES['image']['tmp_name'];

$image_name = time()."_".$_FILES['image']['name'];

$folder = "../uploads/images/events_images/";

if(!file_exists($folder)){
mkdir($folder,0777,true);
}

move_uploaded_file($tmp,$folder.$image_name);

}


/* UPDATE EVENT */

if($_POST['event_id']!=""){

$id = $_POST['event_id'];

if($image_name!=""){

/* DELETE OLD IMAGE */

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

}
else{

mysqli_query($conn,"UPDATE events SET
event_name='$event_name',
description='$description'
WHERE event_id=$id");

}

}


/* INSERT EVENT */

else{

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

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

body{
margin:0;
font-family:'Poppins', sans-serif;
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
padding:30px;
}

/* HEADING */
h2{
color:#5b21b6;
margin-bottom:25px;
text-align:center;
font-weight:600;
}

/* ================= FORM ================= */

form{
background:white;
padding:30px;
border-radius:16px;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
border:1px solid #e9d5ff;
max-width:600px;
margin:0 auto 40px auto; /* center + gap below */
}

/* LABEL */
label{
font-weight:600;
display:block;
margin-top:14px;
color:#4c1d95;
}

/* INPUT */
input,textarea{
width:100%;
padding:12px;
margin-top:6px;
border-radius:10px;
border:1px solid #ddd;
outline:none;
transition:0.3s;
font-size:14px;
}

/* FOCUS */
input:focus, textarea:focus{
border-color:#7c3aed;
box-shadow:0 0 0 2px rgba(124,58,237,0.2);
}

/* BUTTON */
button{
margin-top:18px;
padding:12px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
cursor:pointer;
font-weight:600;
transition:0.3s;
width:100%;
}

button:hover{
transform:scale(1.03);
box-shadow:0 10px 20px rgba(124,58,237,0.3);
}

/* ================= TABLE ================= */

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:16px;
overflow:hidden;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
}

/* HEADER */
th{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
padding:14px;
text-align:left;
font-weight:600;
}

/* DATA */
td{
padding:14px;
border-bottom:1px solid #eee;
vertical-align:middle;
line-height:1.5;
}

/* ROW HOVER */
tr:hover{
background:#f5f3ff;
}

/* IMAGE */
img{
width:70px;
height:70px;
object-fit:cover;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

/* ACTION BUTTONS */
td:last-child{
display:flex;
gap:8px;
align-items:center;
}

/* BUTTON COMMON */
.btn{
padding:6px 14px;
border-radius:20px;
color:white;
text-decoration:none;
font-size:13px;
font-weight:500;
transition:0.3s;
white-space:nowrap;
}

/* EDIT */
.edit{
background:linear-gradient(135deg,#a78bfa,#7c3aed);
}

.edit:hover{
opacity:0.85;
}

/* DELETE */
.delete{
background:linear-gradient(135deg,#f43f5e,#e11d48);
}

.delete:hover{
opacity:0.85;
}


</style>

</head>

<body>

<h2>Manage Events</h2>



<!-- FORM -->

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="event_id"
value="<?php echo isset($edit['event_id']) ? $edit['event_id'] : ''; ?>">

<label>Event Name</label>

<input type="text" name="event_name"
value="<?php echo isset($edit['event_name']) ? $edit['event_name'] : ''; ?>" required>

<label>Description</label>

<textarea name="description"><?php
echo isset($edit['description']) ? $edit['description'] : '';
?></textarea>

<label>Event Image</label>

<input type="file" name="image">

<button type="submit" name="save">

<?php echo isset($edit) ? "Update Event" : "Add Event"; ?>

</button>

</form>


<!-- EVENTS TABLE -->

<table>

<tr>
<th>ID</th>
<th>Event Name</th>
<th>Description</th>
<th>Image</th>
<th>Action</th>
</tr>

<?php

$q = mysqli_query($conn,"SELECT * FROM events");

while($row = mysqli_fetch_assoc($q)){

?>

<tr>

<td><?php echo $row['event_id']; ?></td>

<td><?php echo $row['event_name']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>

<?php
if($row['image']!=""){
?>

<img src="../uploads/images/events_images/<?php echo $row['image']; ?>">

<?php
}
?>

</td>

<td>

<a href="manage_events.php?edit=<?php echo $row['event_id']; ?>" class="btn edit">Edit</a>

<a href="manage_events.php?delete=<?php echo $row['event_id']; ?>"
class="btn delete"
onclick="return confirm('Delete this event?');">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>



</body>
</html>