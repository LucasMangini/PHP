<?php
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	header("Content-Type: text/html; charset=utf-8", True);

	$dir = __DIR__;
	$dataRef = date_format(date_create('today'),"Y-m-d");

	$arquivo_origem = $dir."\Copiar\Copiar_2021-09-16.csv"; // Usando data fixa para exemplo
	$path_destino = $dir."\Colar\\";

	$fp = fopen($dir."/log.txt", "a+");

	// Se falhar ao renomear arquivo antigo (se o mesmo existir)
	if (file_exists($path_destino.'\Copiado.csv') && !rename($path_destino.'\Copiado.csv', $path_destino.'\Copiado_anterior.csv')) {
		$msg = "[".date("d-m-Y H:i:s")."] ERRO: Não foi possível preservar o arquivo anterior. Processo cancelado!\n";
		fwrite($fp, $msg);
		exit();
	}

	// Se falhar ao copiar arquivo novo
	if(!copy($arquivo_origem, $path_destino.'\Copiado.csv')){
		$msg = "[".date("d-m-Y H:i:s")."] ERRO: Não foi possível copiar o arquivo novo. Processo cancelado!\n";
		fwrite($fp, $msg);
		exit();
	}

	fclose($fp);
	exit();
?>