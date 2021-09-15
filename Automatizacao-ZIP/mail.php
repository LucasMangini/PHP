<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	
	function fnMail($assunto, $textoEmail, $destinatarios=array()){
		if (!is_array($destinatarios)) {
			return false;
			exit();
		}

		if (count($destinatarios)<1) {
			return false;
			exit();
		}

		// Load Composer's autoloader
		require 'vendor/autoload.php';

		// Configurar conforme o serviço de email desejado
		$mail = new PHPMailer(true);
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host			= '';
		$mail->SMTPAuth 	= false;
		$mail->Username		= '';
		$mail->Password		= '';
		$mail->SMTPSecure	= false;
		$mail->Port 		= 00; 
		$mail->CharSet 		= "utf-8";

		// Recipients
		$mail->setFrom('');

		for ($i=0; $i < count($destinatarios) ; $i++) { 
			$mail->addAddress($destinatarios[$i]);
		}

		// Content
		$mail->isHTML(false);  
		$mail->Subject = $assunto;
		$mail->Body    = $textoEmail;
		$mail->AltBody = $textoEmail;

		if($mail->send()){
			echo 'Enviado com sucesso !';
			return true; // Email enviado
		}else{
			echo 'Erro ao enviar Email:' . $mail->ErrorInfo;
			return false; // Falha ao enviar email
		}
	}
?>