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
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:
        radial-gradient(circle at top left,#fce7f3,transparent 40%),
        radial-gradient(circle at bottom right,#ddd6fe,transparent 40%),
        linear-gradient(135deg,#fdf2f8,#eef2ff);
    padding:30px;
}

/* HEADINGS */
h2{
    font-size:28px;
    color:#7c3aed;
    margin-bottom:15px;
}
h3{
    margin-top:0;
    color:#6d28d9;
}

/* FORM GLASS CARD */
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

/* INPUTS */
input,select{
    width:100%;
    padding:12px 14px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(0,0,0,0.15);
    font-size:14px;
    outline:none;
    background:rgba(255,255,255,0.9);
}

input:focus, select:focus{
    border-color:#7c3aed;
    box-shadow:0 0 0 2px rgba(124,58,237,0.25);
}

/* BUTTON */
button{
    margin-top:12px;
    padding:12px 28px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#a855f7,#7c3aed);
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 25px rgba(124,58,237,0.45);
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
    background:linear-gradient(135deg,#7c3aed,#6d28d9);
    color:#fff;
    font-weight:600;
    letter-spacing:0.4px;
}

tr{
    transition:0.25s;
}
tr:hover{
    background:rgba(124,58,237,0.08);
}

/* ACTION BUTTONS */
.btn{
    display:inline-block;
    padding:8px 16px;
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
