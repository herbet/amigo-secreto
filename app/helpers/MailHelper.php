<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/phpmailer/src/SMTP.php';

class MailHelper
{
    public static function sendSecretFriendEmail($toEmail, $toName, $friendName)
    {
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP (exemplo com Mailhog local)
            $mail->isSMTP();
            $mail->Host = 'mailhog';
            $mail->Port = 1025;
            
            $mail->SMTPAuth = false;
            //$mail->Username = '';
            //$mail->Password = '';
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // STARTTLS para porta 587

            $mail->setFrom('noreply@amigosecreto.local', 'Amigo Secreto');
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Seu amigo secreto foi sorteado!';
            $mail->Body    = "<p>Olá <strong>$toName</strong>!<br>Você tirou <strong>$friendName</strong> como seu amigo secreto! 🎁</p>";

            $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail para $toEmail: {$mail->ErrorInfo}");
        }
    }
}
