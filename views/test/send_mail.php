<?php
    require_once 'vendor/PHPMailer/src/PHPMailer.php';
    require_once 'vendor/PHPMailer/src/SMTP.php';
    require_once 'vendor/PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = '21shingie@gmail.com'; // SMTP username
        $mail->Password = 'mboi hjvb lbud zkrk'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('21shingie@gmail.com', 'Dave');
        $mail->addAddress('marcjhero.dev@gmail.com', 'Marc Jhero Marcelo');

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Test E-Mail for OTP';
        $mail->Body = 'Your One-Time Password is <strong>42069</strong>';
        $mail->AltBody = 'Send drugs for the homies.';

        // Send the email
        $mail->send();
        echo 'Email has been sent successfully!';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
?>