<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$name = strip_tags($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = strip_tags($_POST['phone'] ?? '');
$message = strip_tags($_POST['message'] ?? '');

if(!$name || !$email || !$message){
    echo 'Please fill all required fields.';
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'skeservices@skeservices.in';
    $mail->Password = 'Satyam@ske321';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('skeservices@skeservices.in', 'Website Contact');
    $mail->addAddress('rupesharma066@gmail.com'); // 👈 change this to test
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "New Enquiry from $name";
    $mail->Body = "
        <h3>Website Contact Form</h3>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Message:</strong><br>$message</p>
    ";
    $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";

    $mail->send();
    echo '✅ Message sent successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
