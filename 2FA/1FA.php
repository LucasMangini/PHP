<?php
	session_start();

	$login = $_POST['login'];
	$senha = $_POST['senha'];
	$email = $_POST['email'];

	if(!isset($login) or !isset($senha)){
		header('Location:index.php?Falha_1');
		exit();
	}

	if($login == "" or $senha == ""){
		header('Location:index.php?Falha_2');
		exit();
	}

	$email = filter_var($email, FILTER_SANITIZE_EMAIL);

	$_SESSION["login"] = $login;
	$_SESSION["senha"] = $senha;
	$_SESSION["email"] = $email;
	$_SESSION['autenticacao'] = 0;

	header('Location:2FA.php');
	exit();
?>