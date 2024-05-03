<?php
    require_once 'vendor/PHPMailer/src/PHPMailer.php';
    require_once 'vendor/PHPMailer/src/SMTP.php';
    require_once 'vendor/PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.hostinger.com';                   // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'no-reply@fmware.shop';               // SMTP username
        $mail->Password   = 'Fmware2024!';                        // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to
    
        // Recipients
        $mail->setFrom('no-reply@fmware.shop', 'FMWare');
        $mail->addAddress('kurei7476@gmail.com', 'Israle Gonzales');     // Add a recipient
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Hindi ako nagpunta dito para makipag away';
        $mail->Body    = '<h1>Natatae ako pre</h1>';
    
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
?>