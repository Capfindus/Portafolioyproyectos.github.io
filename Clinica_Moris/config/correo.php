<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../librerias/PHPMailer/src/Exception.php';
require __DIR__ . '/../librerias/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../librerias/PHPMailer/src/SMTP.php';

function enviarCorreo($destinatario, $asunto, $mensajeHTML) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = 'mffuentesb@gmail.com';
        $mail->Password   = 'zfyb gmkx mgsy dvoy';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('mffuentesb@gmail.com', 'Clinica Moris');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensajeHTML;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Error al enviar correo: {$mail->ErrorInfo}";
    }
}
?>
