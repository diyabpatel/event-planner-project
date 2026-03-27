<?php
include("../db.php");

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM venues WHERE venue_id=$id");
    header("Location: manage_venues.php");
}

// EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM venues WHERE venue_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE
if(isset($_POST['save_venue'])){

    $package_id = $_POST['package_id'];
    $venue_name = $_POST['venue_name'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp, "../uploads/images/venues/annual_day/".$image);
    }

    if($_POST['venue_id'] != ""){
        $vid = $_POST['venue_id'];

        if($image != ""){
            mysqli_query($conn,"UPDATE venues SET 
                package_id='$package_id',
                venue_name='$venue_name',
                price='$price',
                venue_image='$image'
                WHERE venue_id=$vid");
        } else {
            mysqli_query($conn,"UPDATE venues SET 
                package_id='$package_id',
                venue_name='$venue_name',
                price='$price'
                WHERE venue_id=$vid");
        }
    } else {
        mysqli_query($conn,"INSERT INTO venues(package_id,venue_name,price,venue_image)
            VALUES('$package_id','$venue_name','$price','$image')");
    }

    header("Location: manage_venues.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Venues</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#0f172a;
    color:white;
    padding:30px;
}
form{
    background:#1e293b;
    padding:25px;
    border-radius:15px;
    max-width:600px;
}
input,select{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:none;
    background:#334155;
    color:white;
}
button{
    padding:12px 25px;
    border:none;
    border-radius:25px;
    background:#2563eb;
    color:white;
}
table{
    width:100%;
    margin-top:40px;
    border-collapse:collapse;
    background:#1e293b;
}
th{
    background:#2563eb;
    padding:12px;
}
td{
    padding:10px;
    border-bottom:1px solid #334155;
}
img{
    width:80px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}
.btn{
    padding:6px 12px;
    border-radius:20px;
    color:white;
    text-decoration:none;
}
.edit{background:orange;}
.delete{background:red;}
</style>
</head>

<body>

<h2>Manage Venues</h2>

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="venue_id"
value="<?php echo isset($edit['venue_id']) ? $edit['venue_id'] : ''; ?>">

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

<input type="text" name="venue_name" placeholder="Venue Name"
value="<?php echo isset($edit['venue_name']) ? $edit['venue_name'] : ''; ?>" required>

<input type="number" name="price" placeholder="Venue Price"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<input type="file" name="image">

<button type="submit" name="save_venue">
<?php echo isset($edit) && $edit ? "Update Venue" : "Add Venue"; ?>
</button>

</form>

<table>
<tr>
<th>ID</th>
<th>Event</th>
<th>Package</th>
<th>Venue</th>
<th>Price</th>
<th>Image</th>
<th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT venues.*, packages.package_name, events.event_name
FROM venues
JOIN packages ON venues.package_id = packages.package_id
JOIN events ON packages.event_id = events.event_id
");

$folders = ["annual_day","seminar","sports_day","farewell","freshers_party","convocation"];

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
<td><?php echo $row['venue_id']; ?></td>
<td><?php echo $row['event_name']; ?></td>
<td><?php echo $row['package_name']; ?></td>
<td><?php echo $row['venue_name']; ?></td>

<td>&#8377; <?php echo number_format($row['price']); ?></td>

<td>
<?php
$img = $row['venue_image'];
$found = false;

foreach($folders as $folder){
    $path = "../uploads/images/venues/".$folder."/".$img;
    if($img != "" && file_exists($path)){
        echo "<img src='$path'>";
        $found = true;
        break;
    }
}


?>
</td>

<td>
<a href="manage_venues.php?edit=<?php echo $row['venue_id']; ?>" class="btn edit">Edit</a>
<a href="manage_venues.php?delete=<?php echo $row['venue_id']; ?>" class="btn delete"
onclick="return confirm('Delete this venue?');">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>