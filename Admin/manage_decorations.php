<?php
include("../db.php");

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // delete image
    $res = mysqli_query($conn,"SELECT decoration_image FROM decorations WHERE decoration_id=$id");
    $img = mysqli_fetch_assoc($res);

    if($img && $img['decoration_image'] != ""){
        @unlink("../uploads/decorations/".$img['decoration_image']);
    }

    mysqli_query($conn,"DELETE FROM decorations WHERE decoration_id=$id");
    header("Location: manage_decorations.php");
}

// FETCH EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM decorations WHERE decoration_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE
if(isset($_POST['save_decoration'])){

    $package_id = $_POST['package_id'];
    $decoration_name = $_POST['decoration_name'];
    $price = $_POST['price'];

    $image_name = "";

    // ✅ IMAGE UPLOAD
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $image_name = time()."_".$_FILES['image']['name'];

        // folder path
        $target = "../uploads/decorations/".$image_name;

        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    // UPDATE
    if($_POST['decoration_id'] != ""){
        $did = $_POST['decoration_id'];

        if($image_name != ""){
            mysqli_query($conn,"UPDATE decorations SET 
                package_id='$package_id',
                decoration_name='$decoration_name',
                price='$price',
                decoration_image='$image_name'
                WHERE decoration_id=$did");
        } else {
            mysqli_query($conn,"UPDATE decorations SET 
                package_id='$package_id',
                decoration_name='$decoration_name',
                price='$price'
                WHERE decoration_id=$did");
        }
    }
    // INSERT
    else{
        mysqli_query($conn,"INSERT INTO decorations(package_id,decoration_name,price,decoration_image)
        VALUES('$package_id','$decoration_name','$price','$image_name')");
    }

    header("Location: manage_decorations.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Decorations</title>

<style>
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
    padding:30px;
}

h2{text-align:center;color:#5b21b6;}
h3{color:#4c1d95;}

form{
    background:white;
    padding:30px;
    border-radius:16px;
    max-width:600px;
    margin:auto;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

input,select{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #ddd;
}

button{
    margin-top:15px;
    padding:12px;
    border:none;
    border-radius:30px;
    background:#7c3aed;
    color:white;
    width:100%;
}

table{
    width:100%;
    margin-top:30px;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}

th{
    background:#7c3aed;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

img{
    width:80px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}
</style>

</head>
<body>

<h2>Manage Decorations</h2>

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="decoration_id"
value="<?php echo isset($edit['decoration_id']) ? $edit['decoration_id'] : ''; ?>">

<!-- PACKAGE -->
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

<input type="text" name="decoration_name" placeholder="Decoration Name"
value="<?php echo isset($edit['decoration_name']) ? $edit['decoration_name'] : ''; ?>" required>

<input type="number" name="price" placeholder="Decoration Price"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<!-- IMAGE -->
<input type="file" name="image" required>

<button type="submit" name="save_decoration">
<?php echo isset($edit) && $edit ? "Update Decoration" : "Add Decoration"; ?>
</button>

</form>

<table>
<tr>
<th>ID</th>
<th>Event</th>
<th>Package</th>
<th>Decoration</th>
<th>Price</th>
<th>Image</th>
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
<?php
$img = isset($row['decoration_image']) ? $row['decoration_image'] : "";
$path = "../uploads/decorations/".$img;

if($img != "" && file_exists($path)){
    echo "<img src='$path'>";
}else{
    echo "No Image";
}
?>
</td>

<td>
<a href="manage_decorations.php?edit=<?php echo $row['decoration_id']; ?>">Edit</a>
<a href="manage_decorations.php?delete=<?php echo $row['decoration_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>