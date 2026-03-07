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

body{
margin:0;
font-family:Arial;
background:#eef4ff;
padding:30px;
}

h2{
color:#1e3a8a;
}

form{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
max-width:650px;
}

label{
font-weight:bold;
display:block;
margin-top:12px;
}

input,textarea{
width:100%;
padding:12px;
margin-top:5px;
border-radius:10px;
border:1px solid #ccc;
}

button{
margin-top:15px;
padding:12px 25px;
border:none;
border-radius:25px;
background:#2563eb;
color:white;
cursor:pointer;
}

table{
width:100%;
margin-top:40px;
border-collapse:collapse;
background:white;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

th,td{
padding:12px;
text-align:left;
}

th{
background:#2563eb;
color:white;
}

img{
width:80px;
border-radius:8px;
}

.btn{
padding:7px 14px;
border-radius:20px;
color:white;
text-decoration:none;
font-size:13px;
}

.edit{
background:#f59e0b;
}

.delete{
background:#ef4444;
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