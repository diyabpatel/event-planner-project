<?php
include("../db.php");

// DELETE SEAT
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // delete image
    $res = mysqli_query($conn,"SELECT seat_images FROM seats WHERE seat_id=$id");
    $img = mysqli_fetch_assoc($res);

    if($img && $img['seat_images']!=""){
        @unlink("../uploads/images/seats/".$img['seat_images']);
    }

    mysqli_query($conn, "DELETE FROM seats WHERE seat_id=$id");
    header("Location: manage_seats.php");
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM seats WHERE seat_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE SEAT
if(isset($_POST['save_seat'])){

    $package_id = $_POST['package_id'];
    $seat_type  = $_POST['seat_type'];
    $price      = $_POST['price'];

    /* GET EVENT ID */
    $pkg = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT event_id FROM packages WHERE package_id='$package_id'
    "));
    $event_id = $pkg['event_id'];

    $image_name = "";

    // IMAGE UPLOAD
    if(isset($_FILES['image']) && $_FILES['image']['name']!=""){

        $image_name = time()."_".$_FILES['image']['name'];

        $upload_path = "../uploads/images/seats/";

        if(!file_exists($upload_path)){
            mkdir($upload_path,0777,true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $upload_path.$image_name);
    }

    // UPDATE
    if($_POST['seat_id'] != ""){
        $sid = $_POST['seat_id'];

        if($image_name!=""){
            mysqli_query($conn,"UPDATE seats SET 
                event_id='$event_id',
                package_id='$package_id',
                seat_type='$seat_type',
                price='$price',
                seat_images='$image_name'
                WHERE seat_id=$sid");
        } else {
            mysqli_query($conn,"UPDATE seats SET 
                event_id='$event_id',
                package_id='$package_id',
                seat_type='$seat_type',
                price='$price'
                WHERE seat_id=$sid");
        }
    }
    // INSERT
    else{
        mysqli_query($conn,"INSERT INTO seats(event_id,package_id,seat_type,price,seat_images)
            VALUES('$event_id','$package_id','$seat_type','$price','$image_name')");
    }

    header("Location: manage_seats.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Seats</title>

<style>

/* YOUR ORIGINAL CSS — UNCHANGED */

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

.main-content{
margin-left:260px;
padding:30px;
}

h2{
text-align:center;
color:#5b21b6;
margin-bottom:25px;
font-weight:600;
}

h3{
color:#4c1d95;
margin-bottom:10px;
}

form{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
border:1px solid #e9d5ff;
max-width:650px;
margin:0 auto 40px auto;
transition:0.3s;
}

form:hover{
transform:translateY(-3px);
}

input,select{
width:100%;
padding:12px;
margin-top:10px;
border-radius:12px;
border:1px solid #ddd;
outline:none;
transition:0.3s;
font-size:14px;
}

input:focus, select:focus{
border-color:#7c3aed;
box-shadow:0 0 0 3px rgba(124,58,237,0.2);
}

button{
margin-top:20px;
padding:14px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
cursor:pointer;
font-weight:600;
transition:0.3s;
width:100%;
font-size:15px;
}

button:hover{
transform:scale(1.04);
box-shadow:0 10px 25px rgba(124,58,237,0.4);
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
margin-top:20px;
}

th{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
padding:16px;
text-align:left;
font-weight:600;
}

td{
padding:16px;
border-bottom:1px solid #eee;
line-height:1.6;
}

tr:hover{
background:#f5f3ff;
}

td:last-child{
display:flex;
gap:10px;
align-items:center;
}

.btn{
padding:7px 16px;
border-radius:25px;
color:white;
text-decoration:none;
font-size:13px;
font-weight:500;
transition:0.3s;
}

.edit{
background:linear-gradient(135deg,#a78bfa,#7c3aed);
}

.delete{
background:linear-gradient(135deg,#f43f5e,#e11d48);
}

img{
width:80px;
height:60px;
object-fit:cover;
border-radius:10px;
}

</style>

</head>
<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

<h2>Manage Seats</h2>

<form method="post" enctype="multipart/form-data">
<h3><?php echo isset($edit) && $edit ? "Edit Seating" : "Add Seating"; ?></h3>

<input type="hidden" name="seat_id"
value="<?php echo isset($edit['seat_id']) ? $edit['seat_id'] : ''; ?>">

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

<input type="text" name="seat_type" placeholder="Seat Type"
value="<?php echo isset($edit['seat_type']) ? $edit['seat_type'] : ''; ?>" required>

<input type="number" name="price" placeholder="Seat Price"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<input type="file" name="image">

<button type="submit" name="save_seat">
<?php echo isset($edit) && $edit ? "Update Seating" : "Add Seating"; ?>
</button>

</form>

<table>
<tr>
<th>ID</th>
<th>Event</th>
<th>Package</th>
<th>Seat Type</th>
<th>Price</th>
<th>Image</th>
<th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT seats.*, packages.package_name, events.event_name
FROM seats
JOIN packages ON seats.package_id = packages.package_id
JOIN events ON seats.event_id = events.event_id
");

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
<td><?php echo $row['seat_id']; ?></td>
<td><?php echo $row['event_name']; ?></td>
<td><?php echo $row['package_name']; ?></td>
<td><?php echo $row['seat_type']; ?></td>
<td>₹ <?php echo $row['price']; ?></td>

<td>
<?php if(isset($row['seat_images']) && $row['seat_images']!=""){ ?>
<img src="../uploads/images/seats/<?php echo $row['seat_images']; ?>">
<?php } else { ?>
<span style="color:#999;">No Image</span>
<?php } ?>
</td>

<td>
<a href="manage_seats.php?edit=<?php echo $row['seat_id']; ?>" class="btn edit">Edit</a>
<a href="manage_seats.php?delete=<?php echo $row['seat_id']; ?>"
class="btn delete"
onclick="return confirm('Delete this seating option?');">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>