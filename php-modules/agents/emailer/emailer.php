<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


function sendNoReplyEmail($subject, $content, $recipient) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'suburjayabdg.com';  // Replace with your SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@suburjayabdg.com';  // Replace with your email address
        $mail->Password = 'PmKqHO%.awkf';  // Replace with your email account password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Sender and recipient details
        $mail->setFrom('no-reply@suburjayabdg.com', 'Subur Jaya');  // Replace with your email address and name
        $mail->addAddress($recipient);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;

        // Send the email
        $mail->send();
        return 0;
    } catch (Exception $e) {
        return -1;               // 'Error: Unable to send email. ', $mail->ErrorInfo;
    }
}