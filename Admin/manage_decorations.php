<?php
include("../db.php");

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM venues WHERE venue_id=$id");
    header("Location: manage_venues.php");
}

// ADD
if(isset($_POST['save_venue'])){

    $package_id = $_POST['package_id'];
    $venue_name = $_POST['venue_name'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp, "../uploads/images/venues/annual_day/".$image);
    }

    mysqli_query($conn,"INSERT INTO venues(package_id,venue_name,price,venue_image)
        VALUES('$package_id','$venue_name','$price','$image')");

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
    background:#0f172a;
    color:white;
    font-family:Arial;
    padding:30px;
}
form{
    background:#1e293b;
    padding:25px;
    border-radius:15px;
    max-width:600px;
}
select,input{
    width:100%;
    padding:12px;
    margin:12px 0;
    border-radius:10px;
    border:none;
    background:#334155;
    color:white;
}
button{
    background:#2563eb;
    padding:12px 25px;
    border:none;
    border-radius:25px;
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
    border-radius:8px;
    object-fit:cover;
}
.btn{
    padding:6px 12px;
    border-radius:20px;
    color:white;
    text-decoration:none;
}
.delete{background:red;}
</style>
</head>

<body>

<h2>Manage Venues</h2>

<form method="post" enctype="multipart/form-data">

<!-- EVENT -->
<select name="event_id" required>
<option value="">Select Event</option>

<?php
$events = mysqli_query($conn,"SELECT * FROM events");
while($e = mysqli_fetch_assoc($events)){
    echo "<option value='{$e['event_id']}'>{$e['event_name']}</option>";
}
?>
</select>

<!-- PACKAGE -->
<select name="package_id" required>
<option value="">Select Package</option>

<?php
$packages = mysqli_query($conn,"SELECT * FROM packages");
while($p = mysqli_fetch_assoc($packages)){
    echo "<option value='{$p['package_id']}'>
          {$p['package_name']}
          </option>";
}
?>
</select>

<input type="text" name="venue_name" placeholder="Venue Name" required>
<input type="number" name="price" placeholder="Venue Price" required>
<input type="file" name="image">

<button name="save_venue">Add Venue</button>

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

if(!$found){
    echo "<span style='color:gray;'>No Image</span>";
}
?>
</td>

<td>
<a href="?delete=<?php echo $row['venue_id']; ?>" class="btn delete">Delete</a>
</td>

</tr>
<?php } ?>

</table>

</body>
</html>