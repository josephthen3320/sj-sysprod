<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    $recipient = $_POST['recipient'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];

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
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Error: Unable to send email. ', $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
</head>
<body>
<h2>Send Email</h2>
<form method="post" action="">
    <label for="recipient">Recipient:</label>
    <input type="email" name="recipient" required><br>

    <label for="subject">Subject:</label>
    <input type="text" name="subject" required><br>

    <label for="content">Content:</label>
    <textarea name="content" required></textarea><br>

    <input type="submit" name="submit" value="Send Email">
</form>
</body>
</html>
