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
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
    padding:30px;
}

/* HEADING */
h2{
    text-align:center;
    color:#5b21b6;
    margin-bottom:20px;
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
    margin:0 auto 40px auto;
}

/* INPUTS */
input,select{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    transition:0.3s;
    font-size:14px;
}

/* FOCUS */
input:focus, select:focus{
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
    width:80px;
    height:60px;
    object-fit:cover;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,0.15);
}

/* ACTION BUTTON ALIGN */
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