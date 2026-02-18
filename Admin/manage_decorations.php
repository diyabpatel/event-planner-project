<?php
include("../db.php");

// DELETE DECORATION
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM decorations WHERE decoration_id=$id");
    header("Location: manage_decorations.php");
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM decorations WHERE decoration_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE DECORATION
if(isset($_POST['save_decoration'])){

    $package_id = $_POST['package_id'];
    $decoration_name = $_POST['decoration_name'];
    $price = $_POST['price'];

    // UPDATE
    if($_POST['decoration_id'] != ""){
        $did = $_POST['decoration_id'];
        mysqli_query($conn,"UPDATE decorations SET 
            package_id='$package_id',
            decoration_name='$decoration_name',
            price='$price'
            WHERE decoration_id=$did");
    }
    // INSERT
    else{
        mysqli_query($conn,"INSERT INTO decorations(package_id,decoration_name,price)
            VALUES('$package_id','$decoration_name','$price')");
    }

    header("Location: manage_decorations.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Manage Decorations</title>

<style>
body{font-family:Arial;background:#f4f7fb;padding:20px;}
form{background:#fff;padding:15px;border-radius:8px;}
input,select{width:100%;padding:8px;margin:5px 0;}
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

<h2>Manage Decorations</h2>

<!-- ADD / EDIT FORM -->
<form method="post">
<h3><?php echo isset($edit) && $edit ? "Edit Decoration" : "Add Decoration"; ?></h3>

<input type="hidden" name="decoration_id"
value="<?php echo isset($edit['decoration_id']) ? $edit['decoration_id'] : ''; ?>">

<!-- PACKAGE DROPDOWN -->
<select name="package_id" required>
    <option value="">Select Package</option>
    <?php
    $packages = mysqli_query($conn,"
        SELECT packages.*, events.event_name 
        FROM packages 
        JOIN events ON packages.event_id = events.event_id
    ");
    while($p = mysqli_fetch_assoc($packages)){
        $selected = (isset($edit['package_id']) && $edit['package_id']==$p['package_id']) ? "selected" : "";
        echo "<option value='{$p['package_id']}' $selected>
              {$p['event_name']} - {$p['package_name']}
              </option>";
    }
    ?>
</select>

<input type="text" name="decoration_name" placeholder="Decoration Name (Stage / Theme / Floral)"
value="<?php echo isset($edit['decoration_name']) ? $edit['decoration_name'] : ''; ?>" required>

<input type="number" name="price" placeholder="Decoration Price"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<button type="submit" name="save_decoration">
<?php echo isset($edit) && $edit ? "Update Decoration" : "Add Decoration"; ?>
</button>
</form>

<!-- DECORATIONS TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Event</th>
    <th>Package</th>
    <th>Decoration</th>
    <th>Price</th>
    <th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT decorations.*, packages.package_name, events.event_name
FROM decorations
JOIN packages ON decorations.package_id = packages.package_id
JOIN events ON packages.event_id = events.event_id
");

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?php echo $row['decoration_id']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['decoration_name']; ?></td>
    <td>₹ <?php echo $row['price']; ?></td>
    <td>
        <a href="manage_decorations.php?edit=<?php echo $row['decoration_id']; ?>" class="btn edit">Edit</a>
        <a href="manage_decorations.php?delete=<?php echo $row['decoration_id']; ?>"
           class="btn delete"
           onclick="return confirm('Delete this decoration?');">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
