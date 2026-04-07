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
/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins', sans-serif;
}

/* BODY */
body{
background:linear-gradient(135deg,#f5f3ff,#ede9fe);
}

/* MAIN CONTENT */
.main-content{
margin-left:260px;
padding:30px;
}

/* HEADINGS */
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

/* ================= FORM ================= */

form{
background:white;
padding:30px;
border-radius:20px;
max-width:650px;
margin:0 auto 40px auto;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
border:1px solid #e9d5ff;
transition:0.3s;
}

form:hover{
transform:translateY(-3px);
}

/* INPUT */
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

/* FOCUS */
input:focus, select:focus{
border-color:#7c3aed;
box-shadow:0 0 0 3px rgba(124,58,237,0.2);
}

/* BUTTON */
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

/* ================= TABLE ================= */

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 15px 40px rgba(91,33,182,0.15);
margin-top:20px;
}

/* HEADER */
th{
background:linear-gradient(135deg,#7c3aed,#5b21b6);
color:white;
padding:16px;
text-align:left;
font-weight:600;
}

/* DATA */
td{
padding:16px;
border-bottom:1px solid #eee;
line-height:1.6;
}

/* ROW HOVER */
tr:hover{
background:#f5f3ff;
}

/* IMAGE */
img{
width:85px;
height:65px;
object-fit:cover;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

/* ACTION BUTTON ALIGN */
td:last-child{
display:flex;
gap:10px;
align-items:center;
}

/* BUTTON COMMON */
.btn{
padding:7px 16px;
border-radius:25px;
color:white;
text-decoration:none;
font-size:13px;
font-weight:500;
transition:0.3s;
}

/* EDIT */
.edit{
background:linear-gradient(135deg,#a78bfa,#7c3aed);
}

.edit:hover{
transform:scale(1.05);
}

/* DELETE */
.delete{
background:linear-gradient(135deg,#f43f5e,#e11d48);
}

.delete:hover{
transform:scale(1.05);
}
</style>

</head>
<body>

<?php include("admin_sidebar.php"); ?>

<div class="main-content">

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




</form>

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

</td>

<td>
<a href="manage_decorations.php?edit=<?php echo $row['decoration_id']; ?>" class="btn edit">Edit</a>

<a href="manage_decorations.php?delete=<?php echo $row['decoration_id']; ?>" 
class="btn delete"
onclick="return confirm('Delete?')">Delete</a>

</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>