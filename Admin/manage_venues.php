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

    /* ✅ GET EVENT INFO */
    $pkg = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT packages.event_id, events.event_name 
        FROM packages 
        JOIN events ON packages.event_id = events.event_id
        WHERE packages.package_id='$package_id'
    "));

    $event_id = $pkg['event_id'];

    /* 🔥 CONVERT EVENT NAME TO FOLDER */
    $folder = strtolower(str_replace(" ","_",$pkg['event_name']));
    $upload_path = "../uploads/images/venues/".$folder."/";

    if(!file_exists($upload_path)){
        mkdir($upload_path,0777,true);
    }

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp, $upload_path.$image);
    }

    // UPDATE
    if($_POST['venue_id'] != ""){
        $vid = $_POST['venue_id'];

        if($image != ""){
            mysqli_query($conn,"UPDATE venues SET 
                event_id='$event_id',
                package_id='$package_id',
                venue_name='$venue_name',
                price='$price',
                venue_image='$image'
                WHERE venue_id=$vid");
        } else {
            mysqli_query($conn,"UPDATE venues SET 
                event_id='$event_id',
                package_id='$package_id',
                venue_name='$venue_name',
                price='$price'
                WHERE venue_id=$vid");
        }
    } 
    // INSERT
    else {
        mysqli_query($conn,"INSERT INTO venues(event_id,package_id,venue_name,price,venue_image)
            VALUES('$event_id','$package_id','$venue_name','$price','$image')");
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
margin-bottom:25px;
}

form{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
max-width:650px;
margin:0 auto 40px auto;
}

input,select{
width:100%;
padding:12px;
margin-top:10px;
border-radius:12px;
border:1px solid #ddd;
}

button{
margin-top:20px;
padding:14px;
border:none;
border-radius:30px;
background:#7c3aed;
color:white;
cursor:pointer;
width:100%;
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
}

th{
background:#7c3aed;
color:white;
padding:15px;
}

td{
padding:15px;
border-bottom:1px solid #eee;
}

img{
width:85px;
height:65px;
object-fit:cover;
border-radius:12px;
}

.btn{
padding:6px 14px;
border-radius:20px;
color:white;
text-decoration:none;
font-size:13px;
}

.edit{background:#7c3aed;}
.delete{background:#e11d48;}
</style>

</head>
<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

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
JOIN events ON venues.event_id = events.event_id
");

$folders = ["annual_day","seminar","sports_day","farewell_party","freshers_party","convocation"];

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
<td><?php echo $row['venue_id']; ?></td>
<td><?php echo $row['event_name']; ?></td>
<td><?php echo $row['package_name']; ?></td>
<td><?php echo $row['venue_name']; ?></td>
<td>₹ <?php echo number_format($row['price']); ?></td>

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

</div>
</body>
</html>