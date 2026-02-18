<?php
include("../db.php");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Manage Bookings</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f7fb;
    padding:20px;
}
h2{
    color:#1f4fd8;
}
table{
    width:100%;
    border-collapse:collapse;
    background:#ffffff;
    margin-top:20px;
}
th, td{
    padding:10px;
    border:1px solid #ddd;
    text-align:left;
}
th{
    background:#1f4fd8;
    color:white;
}
tr:hover{
    background:#f1f1f1;
}
</style>

</head>
<body>

<h2>Manage Bookings</h2>

<table>
<tr>
    <th>Booking ID</th>
    <th>College Name</th>
    <th>Event Name</th>
    <th>Package Name</th>
    <th>Event Date</th>
    <th>Booking Date</th>
    <th>Total Price</th>
</tr>

<?php
$query = mysqli_query($conn,"
SELECT 
    bookings.booking_id,
    users.college_name,
    events.event_name,
    packages.package_name,
    bookings.event_date,
    bookings.booking_date,
    bookings.total_price
FROM bookings
JOIN users ON bookings.user_id = users.user_id
JOIN events ON bookings.event_id = events.event_id
JOIN packages ON bookings.package_id = packages.package_id
ORDER BY bookings.booking_id DESC
");

while($row = mysqli_fetch_assoc($query)){
?>
<tr>
    <td><?php echo $row['booking_id']; ?></td>
    <td><?php echo $row['college_name']; ?></td>
    <td><?php echo $row['event_name']; ?></td>
    <td><?php echo $row['package_name']; ?></td>
    <td><?php echo $row['event_date']; ?></td>
    <td><?php echo $row['booking_date']; ?></td>
    <td>₹ <?php echo $row['total_price']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
