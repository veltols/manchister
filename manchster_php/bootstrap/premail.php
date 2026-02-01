<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';



//email when new student is added
function sendLeadEmailNotify($empEmail, $empName, $userId){
	global $mailSmtpHost;
	global $mailUserName;
	global $mailUserPass;
	global $mailUserPort;
	
	$mail = new PHPMailer(true);
	try {
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->SMTPOptions = array(
					'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
					)
				);
			
				$mail->Host       = $mailSmtpHost;
				$mail->SMTPAuth   = true;
				$mail->Username   = $mailUserName;
				$mail->Password   = $mailUserPass;
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //ENCRYPTION_STARTTLS
				$mail->Port       = $mailUserPort;
			
				//Recipients
				$mail->setFrom($mailUserName, 'Whiterock Portal');
				$mail->addAddress($empEmail, $empName);          
				$mail->addReplyTo($mailUserName, 'Noreply');
				//$mail->addCC('info@iaidl.org');
				//$mail->addBCC('bcc@iaidl.org');
			
				//Attachments
				//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
			
				//Content
				$mail->isHTML(true);
				
				$bodyContent = '<div style="background: #0078d7;padding: 0.5em;color: #FFF;"><h1>Hello</h1></div>';
				$bodyContent .= '<br>';
				$bodyContent .= '<h2>Dear '.$empName.',</h2>';
				$bodyContent .= '<p>A New lead has been assigned to your account</p>';
				$bodyContent .= '<p>Please login to your account and contact the new lead,</p>';
				$bodyContent .= '<br>';
				$bodyContent .= '<p>Best Regards,<br>Whiterock UAE</p>';
				$bodyContent .= '<br>';
				$bodyContent .= '<hr>';
				$mail->Subject = 'New lead assigned to your account';
				$mail->Body    = $bodyContent;
				$mail->AltBody = 'New lead assigned to your account';
				$mail->send();
	} catch (Exception $e) {
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		//die();
		newError( $userId, "Email-send-error", $mail->ErrorInfo );
	}
	
}














?>