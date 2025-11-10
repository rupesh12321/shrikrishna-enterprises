<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Receiver email — jahan resume aayega
$to = 'rupesharma066@gmail.com';

// Form data
$name = strip_tags($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = strip_tags($_POST['phone'] ?? '');
$position = strip_tags($_POST['position'] ?? '');

if(!$name || !$email){
    echo 'Please fill all required fields.';
    exit;
}

// Resume upload handling
$upload_dir = __DIR__ . '/uploads/';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

$resume_path = '';
if(isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK){
    $fname = basename($_FILES['resume']['name']);
    $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'doc', 'docx'];
    if(!in_array($ext, $allowed)){
        echo '❌ Only PDF, DOC, or DOCX files allowed.';
        exit;
    }
    $resume_path = $upload_dir . uniqid('resume_') . '.' . $ext;
    move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
}

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'skeservices@skeservices.in'; // your Hostinger email
    $mail->Password = 'Satyam@ske321';              // your Hostinger email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email setup
    $mail->setFrom('skeservices@skeservices.in', 'Career Form');
    $mail->addAddress($to); // Receiver (Client mail)
    $mail->addReplyTo($email, $name);

    // Attach resume if uploaded
    if($resume_path && file_exists($resume_path)){
        $mail->addAttachment($resume_path);
    }

    // Mail body
    $mail->isHTML(true);
    $mail->Subject = "New Career Application from $name";
    $mail->Body = "
        <h2>Career Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Position Applied:</strong> $position</p>
        <p>Resume attached below.</p>
    ";
    $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\nPosition: $position";

    $mail->send();
    echo '✅ Application submitted successfully! Thank you.';
} catch (Exception $e) {
    echo "❌ Application could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
