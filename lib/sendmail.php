<?php
    require_once('class.phpmailer.php');
    function sendmail($to,$subject,$message,$name)
    {
                  $mail             = new PHPMailer();
                  $body             = $message;
                  $mail->IsSMTP();
                  $mail->Host       = "smtp.live.com";                  
                  $mail->SMTPAuth   = true;
                  $mail->Host       = "smtp.live.com";
                  $mail->Port       = 25;
                  $mail->Username   = "kurokikaze@hotmail.fr";
                  $mail->Password   = "Sonia1987";
                  $mail->SMTPSecure = 'tls';
                  $mail->SetFrom('kurokikaze@hotmail.fr', 'Webforum BVD');
                  $mail->AddReplyTo("kurokikaze@hotmail.fr","Webforum BVD");
                  $mail->Subject    = $subject;
                  $mail->AltBody    = "veuillez utiliser un visioneur de mail qui permet l'affichage HTML";
                  $mail->MsgHTML($body);
                  $address = $to;
                  $mail->AddAddress($address, $name);
                  if(!$mail->Send()) {
                      return 0;
                  } else {
                        return 1;
                  }
    }
?>