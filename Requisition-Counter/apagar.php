<?php
	session_start();
	$_SESSION["count"] = 1;
	session_destroy();

	echo '<pre>';
		print_r($_SESSION);
	echo '</pre>';
?>

<a href="index.php"><br>VOLTAR</a>