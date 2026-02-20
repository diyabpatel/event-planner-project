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
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:
        radial-gradient(circle at top,#1e293b,transparent 50%),
        linear-gradient(135deg,#0f172a,#1e293b);
    padding:30px;
    color:#f1f5f9;
}

/* HEADINGS */
h2{
    font-size:28px;
    color:#38bdf8;
    margin-bottom:15px;
}
h3{
    margin-top:0;
    color:#7dd3fc;
}

/* GLASS FORM */
form{
    background:rgba(30,41,59,0.6);
    backdrop-filter:blur(18px);
    padding:25px;
    border-radius:18px;
    box-shadow:
        0 15px 40px rgba(0,0,0,0.6),
        inset 0 1px 1px rgba(255,255,255,0.05);
    max-width:650px;
}

/* INPUTS */
input,select{
    width:100%;
    padding:12px 14px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.15);
    font-size:14px;
    outline:none;
    background:rgba(15,23,42,0.8);
    color:#fff;
}

input:focus, select:focus{
    border-color:#38bdf8;
    box-shadow:0 0 0 2px rgba(56,189,248,0.3);
}

/* BUTTON */
button{
    margin-top:12px;
    padding:12px 28px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#38bdf8,#0ea5e9);
    color:#0f172a;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 25px rgba(56,189,248,0.45);
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
    background:rgba(30,41,59,0.6);
    backdrop-filter:blur(18px);
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
}

th,td{
    padding:14px 15px;
    text-align:left;
}

th{
    background:linear-gradient(135deg,#0ea5e9,#0284c7);
    color:#fff;
    font-weight:600;
    letter-spacing:0.4px;
}

tr{
    transition:0.25s;
}
tr:hover{
    background:rgba(56,189,248,0.08);
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
