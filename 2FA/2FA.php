<?php
	session_start();

	/* ----- Configurar PHPMailer ----- */
	require "PHPMailer/src/PHPMailer.php";
	require "PHPMailer/src/SMTP.php";
	require "PHPMailer/src/Exception.php";
	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
   	use PHPMailer\PHPMailer\Exception;
	$mail = new PHPMailer();

	if($_SESSION["login"]){
		/* ----- Gerar Código ----- */
		if($_SESSION['autenticacao'] == 0){
			$_SESSION['autenticacao'] =  rand(10000, 99999);
		}

		$mail->isSMTP();
		$mail->CharSet 		= "UTF-8";
		$mail->SMTPAuth 	= true;
		$mail->Host 		= "mail.dominio.com";
		$mail->Username 	= "seu@email.com";
		$mail->Password 	= "suasenha";
		$mail->SMTPSecure 	= "TLS";
		$mail->Port       	= 587;

		$mail->setFrom('seu@email.com', 'Nome');
	    $mail->addAddress($_SESSION['email']);

		$mail->isHTML(true);
		$mail->Subject 	= "Código de Segurança";
		$mail->Body 	= "Seu código de segurança é: <b>".$_SESSION['autenticacao']."</b>";

		$mail->Send();

		if(!$mail->ErrorInfo){
			$retornoEmail = "O código de segurança foi enviado para seu e-mail!";
		}else{
			$retornoEmail = "Houve um erro ao enviar o código de segurança para o seu e-mail<br>Erro: ".$mail->ErrorInfo;
		}
	}else{
		header('Location:index.php?r=Nao_logado');
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>2FA</title>
	</head>

	<body>
		<form method="POST" action="">
	        <input type="text" name="validacao" placeholder="Código" autocomplete="off">
	        <input type="submit" name="enviar" value="Enviar">
	    </form>

	    <div>
	    	<?php
	    		echo $retornoEmail.'<br><br>';

	    		if(isset($_POST['validacao'])){
					if($_SESSION["autenticacao"] == $_POST['validacao']){
						echo 'Código válido!<br>';
						echo "<a href='sair.php'>Sair</a><br><br>";
					}else{
						echo 'Código inválido!<br>';
						echo "<a href='sair.php'>Sair</a><br><br>";
					}
				}
	    	?>
	    </div>
	</body>
</html>