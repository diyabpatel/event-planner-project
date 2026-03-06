<?php

$conn = mysqli_connect("localhost", "root", "", "eventhub");

if(!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}

/* AUTO UPDATE PAYMENT STATUS AFTER EVENT DATE */

mysqli_query($conn,"
UPDATE bookings
SET 
payment_status='Full Payment Done',
advance_paid = total_price,
remaining_amount = 0
WHERE event_date < CURDATE()
AND payment_status != 'Full Payment Done'
");

?>