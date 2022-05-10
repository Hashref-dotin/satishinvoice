<?php

include_once 'PHPMailerAutoload.php';
	function sendMail_New($toaddress,$subject,$message,$toname = 'User',$attachment=false) {
		global $sqlDB;
		if( empty($toaddress)) {
			die("Mailer Error: Sendor email address is missing");
		}
		/*
		notifications@hitbullseye.co.in	
		Administrator - Bulls Eye	
		AKIA2CWTQOJS4HDD4ZFQ	
		BNbYa9HxWmxW/RD822EgOqB5T1PqxuhFPpDA2YZ/uWwh	
		email-smtp.us-west-2.amazonaws.com
			587*/
		//$getHostData = $sqlDB->queryRow("EXEC GetEmailSMTP");
		$Host_name = 'email-smtp.us-west-2.amazonaws.com';
		$Host_port = 587;
		$Host_username = 'AKIA2CWTQOJS4HDD4ZFQ';
		$Host_password = 'BNbYa9HxWmxW/RD822EgOqB5T1PqxuhFPpDA2YZ/uWwh';
		$fromemail = 'notifications@hitbullseye.co.in	';
		$fromename = 'Administrator - Bulls Eye	';
		$mail = new PHPMailer;
		try { 
			$mail->isSMTP();
			$mail->SMTPDebug = 2;
			$mail->Host = $Host_name;
			$mail->Port = $Host_port;
			$mail->Username = $Host_username;
			$mail->Password = $Host_password;
			$mail->SMTPAuth = true;
			$mail->setFrom($fromemail, $fromename);
			$mail->addReplyTo($fromemail, $fromename);
			if(is_array($toaddress)){
				foreach($toaddress as $email){
					$mail->addAddress($email, $email);
				}
			} else {
				$mail->addAddress($toaddress, $toname);
			}
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			if(!empty($attachment) && file_exists($attachment)) {
				$mail->addAttachment($attachment);
			}
			if (!$mail->send()) {
				die("Mailer Error: " . $mail->ErrorInfo);
			} else {
				return true;
			}
		} catch (Exception $e) {
			die("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
		}
	}

$toaddress = "raj@cybrain.co.in";
$toname='Mathan raj';
$subject = "Test Mail Subject";
$message = "Just to check the mail configuration working"; // HTML  tags

		
		sendMail_New($toaddress,$subject,$message,$toname);
