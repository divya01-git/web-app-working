<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'projectclgsrmcem@gmail.com';    // Your Gmail
        $mail->Password   = 'pngncjxayddnsshu';              // Your App Password (no spaces!)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('projectclgsrmcem@gmail.com', 'Rainwater Planner');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Rainwater Harvesting Planner';
        $mail->Body    = "<h3>Your OTP is: <strong>$otp</strong></h3>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Optionally log the error: $e->getMessage()
        return false;
    }
}
?>
