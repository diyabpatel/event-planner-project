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
body{font-family:Arial;background:#f4f7fb;padding:20px;}
form{background:#fff;padding:15px;border-radius:8px;}
input,textarea,select{width:100%;padding:8px;margin:5px 0;}
button{padding:8px 15px;}
table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;}
th,td{padding:10px;border:1px solid #ddd;text-align:left;}
th{background:#1f4fd8;color:white;}
.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    color:white;
    margin-right:5px;
}
.edit{background:#f0ad4e;}
.delete{background:#d9534f;}
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
