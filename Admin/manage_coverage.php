<?php
include("../db.php");

// DELETE COVERAGE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM coverage WHERE coverage_id=$id");
    header("Location: manage_coverage.php");
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM coverage WHERE coverage_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE COVERAGE
if(isset($_POST['save_coverage'])){

    $package_id = $_POST['package_id'];
    $coverage_type = $_POST['coverage_type'];
    $price = $_POST['price'];

    // UPDATE
    if($_POST['coverage_id'] != ""){
        $cid = $_POST['coverage_id'];
        mysqli_query($conn,"UPDATE coverage SET 
            package_id='$package_id',
            coverage_type='$coverage_type',
            price='$price'
            WHERE coverage_id=$cid");
    }
    // INSERT
    else{
        mysqli_query($conn,"INSERT INTO coverage(package_id,coverage_type,price)
            VALUES('$package_id','$coverage_type','$price')");
    }

    header("Location: manage_coverage.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Manage Coverage</title>

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

/* HEADINGS */
h2{
    text-align:center;
    color:#5b21b6;
    margin-bottom:20px;
    font-weight:600;
}

h3{
    margin-top:0;
    color:#4c1d95;
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

<h2>Manage Coverage</h2>

<!-- ADD / EDIT FORM -->
<form method="post">
<h3><?php echo isset($edit) && $edit ? "Edit Coverage" : "Add Coverage"; ?></h3>

<input type="hidden" name="coverage_id"
value="<?php echo isset($edit['coverage_id']) ? $edit['coverage_id'] : ''; ?>">

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

<input type="text" name="coverage_type" placeholder="Coverage Type (Photography / Videography / Cinematic)"
value="<?php echo isset($edit['coverage_type']) ? $edit['coverage_type'] : ''; ?>" required>

<input type="number" name="price" placeholder="Coverage Price"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<button type="submit" name="save_coverage">
<?php echo isset($edit) && $edit ? "Update Coverage" : "Add Coverage"; ?>
</button>
</form>

<!-- COVERAGE TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Event</th>
    <th>Package</th>
    <th>Coverage Type</th>
    <th>Price</th>
    <th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT coverage.*, packages.package_name, events.event_name
FROM coverage
JOIN packages ON coverage.package_id = packages.package_id
JOIN events ON packages.event_id = events.event_id
");

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?php echo $row['coverage_id']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['coverage_type']; ?></td>
    <td>₹ <?php echo $row['price']; ?></td>
    <td>
        <a href="manage_coverage.php?edit=<?php echo $row['coverage_id']; ?>" class="btn edit">Edit</a>
        <a href="manage_coverage.php?delete=<?php echo $row['coverage_id']; ?>"
           class="btn delete"
           onclick="return confirm('Delete this coverage option?');">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
