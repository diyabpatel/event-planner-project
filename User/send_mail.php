<?php
// PHPMailer 5.2.27 (NO namespaces)
require '../PHPMailer-master/class.phpmailer.php';
require '../PHPMailer-master/class.smtp.php';

/**
 * Send booking confirmation email
 *
 * @param string $toEmail
 * @param int    $bookingId
 * @param float  $advanceAmount
 * @return bool
 */
function sendBookingMail($toEmail, $bookingId, $advanceAmount)
{
    $mail = new PHPMailer(true);

    // SMTP CONFIG
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🔴 CHANGE THESE TWO
    $mail->Username = 'ssagrwalclgnavsari@gmail.com';      // your gmail
    $mail->Password = 'swjvmtmkneglsdya';         // 16-char app password (no spaces)

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // EMAIL HEADERS
    $mail->From = 'ssagrwalclgnavsari@gmail.com';
    $mail->FromName = 'Event Planner';

    $mail->addAddress($toEmail);

    // EMAIL CONTENT
    $mail->isHTML(false); // simple text mail (safe for demo)
    $mail->Subject = 'Booking Payment Successful';

    $mail->Body =
"Payment Successful

Your booking has been confirmed.

Booking ID : $bookingId
Advance Paid : Rs. $advanceAmount

Thank you for choosing Event Planner.
";

    // SEND
    if(!$mail->send()){
        return false;
    }
    return true;
}