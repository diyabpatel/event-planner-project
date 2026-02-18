<?php
include("../db.php");

// DELETE PACKAGE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM packages WHERE package_id=$id");
    header("Location: manage_packages.php");
}

// FETCH DATA FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM packages WHERE package_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE PACKAGE
if(isset($_POST['save_package'])){

    $event_id = $_POST['event_id'];
    $package_name = $_POST['package_name'];
    $description = $_POST['description'];

    // UPDATE
    if($_POST['package_id'] != ""){
        $pid = $_POST['package_id'];
        mysqli_query($conn, "UPDATE packages SET 
            event_id='$event_id',
            package_name='$package_name',
            description='$description'
            WHERE package_id=$pid");
    }
    // INSERT
    else{
        mysqli_query($conn, "INSERT INTO packages(event_id,package_name,description)
            VALUES('$event_id','$package_name','$description')");
    }

    header("Location: manage_packages.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<title>Manage Packages</title>

<style>
body{font-family:Arial;background:#f4f7fb;padding:20px;}
form{background:#fff;padding:15px;border-radius:8px;}
input,textarea,select{width:100%;padding:8px;margin:5px 0;}
button{padding:8px 15px;}
table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;}
th,td{padding:10px;border:1px solid #ddd;text-align:left;}
th{background:#1f4fd8;color:white;}
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

<h2>Manage Packages</h2>

<!-- ADD / EDIT FORM -->
<form method="post">
<h3><?php echo isset($edit) && $edit ? "Edit Package" : "Add Package"; ?></h3>

<input type="hidden" name="package_id"
value="<?php echo isset($edit['package_id']) ? $edit['package_id'] : ''; ?>">

<!-- EVENT DROPDOWN -->
<select name="event_id" required>
    <option value="">Select Event</option>
    <?php
    $events = mysqli_query($conn, "SELECT * FROM events");
    while($e = mysqli_fetch_assoc($events)){
        $selected = (isset($edit['event_id']) && $edit['event_id']==$e['event_id']) ? "selected" : "";
        echo "<option value='{$e['event_id']}' $selected>{$e['event_name']}</option>";
    }
    ?>
</select>

<input type="text" name="package_name" placeholder="Package Name (Basic / Premium)"
value="<?php echo isset($edit['package_name']) ? $edit['package_name'] : ''; ?>" required>

<textarea name="description" placeholder="Package Description" required><?php
echo isset($edit['description']) ? $edit['description'] : '';?></textarea>

<button type="submit" name="save_package">
<?php echo isset($edit) && $edit ? "Update Package" : "Add Package"; ?>
</button>
</form>

<!-- PACKAGES TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Event</th>
    <th>Package Name</th>
    <th>Description</th>
    <th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT packages.*, events.event_name 
FROM packages 
JOIN events ON packages.event_id = events.event_id
");

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?php echo $row['package_id']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['description']; ?></td>
    <td>
        <a href="manage_packages.php?edit=<?php echo $row['package_id']; ?>" class="btn edit">Edit</a>
        <a href="manage_packages.php?delete=<?php echo $row['package_id']; ?>"
           class="btn delete"
           onclick="return confirm('Delete this package?');">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
