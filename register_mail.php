<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

/**
 * Sends a verification email to user after registration
 *
 * @param string $fullName
 * @param string $email
 * @param string $verificationCode
 * @return bool
 */
function sendVerificationEmail($fullName, $email, $verificationCode) {
    $mail = new PHPMailer(true);

    try {
        // ✅ SMTP Config (Gmail Example)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ytrbulsubustosofficial@gmail.com';  // replace with your email
        $mail->Password   = 'rrlo ayyo uxfo uwks';        // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ✅ Email Headers
        $mail->setFrom('ementorguidance@gmail.com', 'eMentor CITE');
        $mail->addAddress($email, $fullName);
        $mail->addReplyTo('ementorguidance@gmail.com', 'eMentor Support');

        // ✅ Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your eMentor Account';
        $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Hi, $fullName!</h2>
                <p>Thank you for registering with <strong>eMentor</strong>.</p>
                <p>Your verification code is:</p>
                <h2 style='color:#2e6c80;'>$verificationCode</h2>
                <p>Please enter this code on the verification page to complete your registration.</p>
                <br>
                <p>Best regards,<br><strong>eMentor Team</strong></p>
                <hr>
                <small>This is an automated message. Do not reply directly.</small>
            </body>
            </html>
        ";

        // ✅ Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error ({$email}): " . $mail->ErrorInfo);
        return false;
    }
}
?>
