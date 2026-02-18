<?php
include("../db.php");

// DELETE EVENT
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM events WHERE event_id=$id");
    header("Location: manage_events.php");
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM events WHERE event_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE
if(isset($_POST['save'])){
    $event_name = $_POST['event_name'];
    $description = $_POST['description'];
    $image = $_POST['image'];   // image path
    $page = $_POST['page'];

    if($_POST['event_id']!=""){
        $id = $_POST['event_id'];
        mysqli_query($conn,"UPDATE events SET 
            event_name='$event_name',
            description='$description',
            image='$image',
            page='$page'
            WHERE event_id=$id");
    }else{
        mysqli_query($conn,"INSERT INTO events(event_name,description,image,page)
            VALUES('$event_name','$description','$image','$page')");
    }
    header("Location: manage_events.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Manage Events</title>

<style>
body{font-family:Arial;background:#f4f7fb;padding:20px;}
form{background:#fff;padding:15px;border-radius:8px;}
input,textarea{width:100%;padding:8px;margin:5px 0;}
button{padding:8px 15px;}
table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;}
th,td{padding:10px;border:1px solid #ddd;text-align:left;}
th{background:#1f4fd8;color:white;}
img{width:80px;border-radius:6px;}
.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    color:white;
    margin-right:5px;
}
.edit{background:#f0ad4e;}
.delete{background:#d9534f;}
</style>

</head>
<body>

<h2>Manage Events</h2>

<!-- ADD / EDIT FORM -->
<form method="post">
<h3><?php echo isset($edit) && $edit ? "Edit Event" : "Add Event"; ?></h3>

<input type="hidden" name="event_id"
value="<?php echo isset($edit['event_id']) ? $edit['event_id'] : ''; ?>">

<input type="text" name="event_name" placeholder="Event Name"
value="<?php echo isset($edit['event_name']) ? $edit['event_name'] : ''; ?>" required>

<textarea name="description" placeholder="Description" required><?php
echo isset($edit['description']) ? $edit['description'] : '';
?></textarea>

<input type="text" name="image" placeholder="Image Path"
value="<?php echo isset($edit['image']) ? $edit['image'] : ''; ?>" required>

<input type="text" name="page" placeholder="Page Path"
value="<?php echo isset($edit['page']) ? $edit['page'] : ''; ?>" required>

<button type="submit" name="save">
<?php echo isset($edit) && $edit ? "Update Event" : "Add Event"; ?>
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

    <!-- IMAGE PREVIEW -->
    <td>
        <img src="../<?php echo $row['image']; ?>" alt="event image">
    </td>

    <!-- ACTION BUTTONS -->
    <td>
        <a href="manage_events.php?edit=<?php echo $row['event_id']; ?>" class="btn edit">Edit</a>
        <a href="manage_events.php?delete=<?php echo $row['event_id']; ?>" 
           class="btn delete"
           onclick="return confirm('Delete this event?');">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
