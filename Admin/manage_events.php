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
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:
        radial-gradient(circle at top left,#dbeafe,transparent 40%),
        radial-gradient(circle at bottom right,#bfdbfe,transparent 40%),
        linear-gradient(135deg,#eef4ff,#f8fbff);
    padding:30px;
}

/* PAGE TITLE */
h2{
    font-size:28px;
    color:#1e3a8a;
    margin-bottom:15px;
}
h3{
    margin-top:0;
    color:#1e40af;
}

/* FORM */
form{
    background:rgba(255,255,255,0.65);
    backdrop-filter:blur(18px);
    padding:25px;
    border-radius:18px;
    box-shadow:
        0 15px 40px rgba(0,0,0,0.15),
        inset 0 1px 1px rgba(255,255,255,0.6);
    max-width:650px;
}

input,textarea{
    width:100%;
    padding:12px 14px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(0,0,0,0.15);
    font-size:14px;
    outline:none;
    background:rgba(255,255,255,0.9);
}

input:focus, textarea:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,0.25);
}

/* BUTTON */
button{
    margin-top:10px;
    padding:12px 26px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 25px rgba(37,99,235,0.45);
    transition:all 0.3s ease;
}
button:hover{
    transform:translateY(-2px) scale(1.05);
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:40px;
    background:rgba(255,255,255,0.65);
    backdrop-filter:blur(18px);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

th,td{
    padding:14px 15px;
    text-align:left;
}

th{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    font-weight:600;
    letter-spacing:0.4px;
}

tr{
    transition:0.25s;
}
tr:hover{
    background:rgba(37,99,235,0.08);
}

/* IMAGE */
img{
    width:80px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

/* ACTION BUTTONS */
.btn{
    display:inline-block;
    padding:8px 14px;
    border-radius:20px;
    text-decoration:none;
    color:#fff;
    font-size:13px;
    font-weight:600;
    margin-right:6px;
    transition:all 0.3s ease;
}

.edit{
    background:linear-gradient(135deg,#f59e0b,#d97706);
    box-shadow:0 6px 20px rgba(245,158,11,0.5);
}
.delete{
    background:linear-gradient(135deg,#ef4444,#dc2626);
    box-shadow:0 6px 20px rgba(239,68,68,0.5);
}

.btn:hover{
    transform:translateY(-2px) scale(1.05);
}
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
