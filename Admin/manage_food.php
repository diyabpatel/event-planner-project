<?php
include("../db.php");

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM food WHERE food_id=$id");
    header("Location: manage_food.php");
}

// FETCH FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM food WHERE food_id=$id");
    $edit = mysqli_fetch_assoc($res);
}

// ADD / UPDATE
if(isset($_POST['save_food'])){

    $package_id = $_POST['package_id'];
    $food_type = $_POST['food_type'];
    $menu = $_POST['menu'];
    $price = $_POST['price'];

    // UPDATE
    if($_POST['food_id'] != ""){
        $fid = $_POST['food_id'];
        mysqli_query($conn,"UPDATE food SET 
            package_id='$package_id',
            food_type='$food_type',
            menu='$menu',
            price='$price'
            WHERE food_id=$fid");
    }
    // INSERT
    else{
        mysqli_query($conn,"INSERT INTO food(package_id,food_type,menu,price)
            VALUES('$package_id','$food_type','$menu','$price')");
    }

    header("Location: manage_food.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Manage Food</title>

<style>
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:
        radial-gradient(circle at top left,#dbeafe,transparent 40%),
        radial-gradient(circle at bottom right,#bfdbfe,transparent 40%),
        linear-gradient(135deg,#eef4ff,#f8fbff);
    padding:30px;
}

/* TITLES */
h2{
    font-size:28px;
    color:#1e3a8a;
    margin-bottom:15px;
}
h3{
    margin-top:0;
    color:#1e40af;
}

/* FORM */
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
input,textarea,select{
    width:100%;
    padding:12px 14px;
    margin:10px 0;
    border-radius:12px;
    border:1px solid rgba(0,0,0,0.15);
    font-size:14px;
    outline:none;
    background:rgba(255,255,255,0.9);
}

input:focus, textarea:focus, select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,0.25);
}

/* BUTTON */
button{
    margin-top:10px;
    padding:12px 28px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:#fff;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 25px rgba(34,197,94,0.45);
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
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;
    font-weight:600;
    letter-spacing:0.4px;
}

tr{
    transition:0.25s;
}
tr:hover{
    background:rgba(37,99,235,0.08);
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

<h2>Manage Food</h2>

<!-- ADD / EDIT FORM -->
<form method="post">
<h3><?php echo isset($edit) && $edit ? "Edit Food" : "Add Food"; ?></h3>

<input type="hidden" name="food_id"
value="<?php echo isset($edit['food_id']) ? $edit['food_id'] : ''; ?>">

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

<select name="food_type" required>
    <option value="">Select Food Type</option>
    <option value="Breakfast" <?php if(isset($edit['food_type']) && $edit['food_type']=="Breakfast") echo "selected"; ?>>Breakfast</option>
    <option value="Lunch" <?php if(isset($edit['food_type']) && $edit['food_type']=="Lunch") echo "selected"; ?>>Lunch</option>
    <option value="Dinner" <?php if(isset($edit['food_type']) && $edit['food_type']=="Dinner") echo "selected"; ?>>Dinner</option>
</select>

<textarea name="menu" placeholder="Menu Details" required><?php
echo isset($edit['menu']) ? $edit['menu'] : '';
?></textarea>

<input type="number" name="price" placeholder="Price per person"
value="<?php echo isset($edit['price']) ? $edit['price'] : ''; ?>" required>

<button type="submit" name="save_food">
<?php echo isset($edit) && $edit ? "Update Food" : "Add Food"; ?>
</button>
</form>

<!-- FOOD TABLE -->
<table>
<tr>
    <th>ID</th>
    <th>Event</th>
    <th>Package</th>
    <th>Type</th>
    <th>Menu</th>
    <th>Price</th>
    <th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
SELECT food.*, packages.package_name, events.event_name
FROM food
JOIN packages ON food.package_id = packages.package_id
JOIN events ON packages.event_id = events.event_id
");

while($row = mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?php echo $row['food_id']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['food_type']; ?></td>
    <td><?php echo $row['menu']; ?></td>
    <td>₹ <?php echo $row['price']; ?></td>
    <td>
        <a href="manage_food.php?edit=<?php echo $row['food_id']; ?>" class="btn edit">Edit</a>
        <a href="manage_food.php?delete=<?php echo $row['food_id']; ?>"
           class="btn delete"
           onclick="return confirm('Delete this food item?');">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
