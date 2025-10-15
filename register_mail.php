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
        // ✅ SMTP Configuration (Gmail Example)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ytrbulsubustosofficial@gmail.com'; // your Gmail
        $mail->Password   = 'rrlo ayyo uxfo uwks';              // your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ✅ Sender & Recipient
        $mail->setFrom('ytrbulsubustosofficial@gmail.com', 'CareerScope');
        $mail->addAddress($email, $fullName);
        $mail->addReplyTo('ytrbulsubustosofficial@gmail.com', 'CareerScope Support');

        // ✅ Email Subject & Body (Gray + Gold Theme)
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your CareerScope Account';

        $mail->Body = "
        <html>
        <body style='margin:0; padding:0; background-color:#f0f0f0; font-family:Segoe UI, Tahoma, sans-serif;'>
            <div style='max-width:600px; margin:40px auto; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
                
                <!-- Header -->
                <div style='background:linear-gradient(135deg, #555, #777); color:white; text-align:center; padding:25px 10px;'>
                    <h1 style='margin:0; font-size:26px;'>CareerScope</h1>
                    <p style='margin:5px 0 0; font-size:14px; opacity:0.9;'>Empowering students with data-driven career guidance</p>
                </div>

                <!-- Body -->
                <div style='padding:30px 40px; color:#333;'>
                    <h2 style='margin-top:0; color:#555;'>Hello, $fullName!</h2>
                    <p style='font-size:15px; line-height:1.6;'>
                        Thank you for registering with <strong>CareerScope</strong>! To complete your registration, please use the verification code below:
                    </p>

                    <div style='text-align:center; margin:30px 0;'>
                        <div style='display:inline-block; background:#ffcc00; color:#004080; font-size:22px; letter-spacing:3px; font-weight:bold; padding:15px 25px; border-radius:8px;'>
                            $verificationCode
                        </div>
                    </div>

                    <p style='font-size:15px; line-height:1.6;'>
                        Enter this code on the verification page to activate your account.<br>
                        If you didn’t request this registration, please ignore this message.
                    </p>

                    <p style='margin-top:30px; font-size:15px;'>
                        Best regards,<br>
                        <strong>The CareerScope Team</strong>
                    </p>
                </div>

                <!-- Footer -->
                <div style='background:#f8f8f8; padding:12px 10px; text-align:center; font-size:12px; color:#777;'>
                    © " . date('Y') . " CareerScope — Empowering Students with Data-Driven Guidance<br>
                    <small>This is an automated message. Do not reply.</small>
                </div>
            </div>
        </body>
        </html>
        ";

        // ✅ Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer Error (' . $email . '): ' . $mail->ErrorInfo);
        return false;
    }
}
?>
