<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include(app_path() . '/../vendor/phpmailer/phpmailer/src/Exception.php');

include(app_path() . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php');

include(app_path() . '/../vendor/phpmailer/phpmailer/src/SMTP.php');

class SendEmailHelper extends Controller
{
    public static function sendEmail($subject, $template, $principal = array(), $cc = array())
    {
        try {

            $mail = new PHPMailer;

            $mail->isSMTP();

            $mail->SMTPDebug = 3;

            $mail->Debugoutput = 'html';

            $mail->Host = "smtp.gmail.com";

            $mail->CharSet = 'UTF-8';

            $mail->Port = 587;

            $mail->SMTPAuth = true;

            $mail->SMTPAutoTLS = false;

            $mail->SMTPSecure = 'tls'; // To enable TLS/SSL encryption change to 'tls'

            // $mail->AuthType = "CRAM-MD5";

            $mail->Username = "luisanacamacho25@gmail.com";

            $mail->Password = "luchimeza25";

            $mail->setFrom('luisanacamacho25@gmail.com');
            foreach ($principal as $principa) {
                $mail->addAddress($principa->email, $principa->name);
            }

            foreach ($cc as $c) {
                $mail->addCC($c->email);
            }

            $mail->Subject = $subject;

            $mail->isHTML(true);

            $mail->Body = $template;

            if (!$mail->send()) {
                $response = 'No se pudo enviar el mensaje';
                //$response = $mail->ErrorInfo;
            } else {
                $response = 1;
            }

            } catch (Exception $e) {
                $response = "Message could not be sent. Mailer Error: {.$mail->ErrorInfo.}";
            }

            return $response;
    }
}
