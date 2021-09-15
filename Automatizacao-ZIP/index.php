<?php
	require_once("mail.php");
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');

	$dir = __DIR__;
	$nome_arquivos = array();
	$emails = array('exemplo@email.com'); // Lista de emails caso dê algum erro
	$dataRef = date_format(date_create('today'),"d-m-Y");

	$path_origem = $dir."\Copiar\\";
	$path_destino =  $dir."\Colar\\";

	$arquivo_log = $dir."/log.txt";
	$fp = fopen($arquivo_log, "a+");

	// Limpar o diretório antes de copiar novos arquivos
	if(is_dir($path_destino)){
		$diretorio = dir($path_destino);
		while($arquivo = $diretorio->read()){
			if(($arquivo != '.') && ($arquivo != '..')){
				unlink($path_destino.$arquivo);
			}
		}
		$diretorio->close();
	}

	// Verificar arquivos para cópia
	$i = 0;
	$tentativa = 0;
	do{
		$diretorio = dir($path_origem);
		while($arquivo = $diretorio->read()){
			if(	substr($arquivo, 0, -4) == "arquivo01_14-09-2021" || // Como exemplo foi usada uma data fixa, para data variável usar 'arquivo01_$dataRef'
				substr($arquivo, 0, -4) == "arquivo02_14-09-2021" || // Como exemplo foi usada uma data fixa, para data variável usar 'arquivo02_$dataRef'
				substr($arquivo, 0, -4) == "arquivo03_14-09-2021" ){ // Como exemplo foi usada uma data fixa, para data variável usar 'arquivo03_$dataRef'
					$nome_arquivos[$i] = $arquivo;
					$i++;
			}
		}
		$diretorio->close();

		if(count($nome_arquivos) < 3){
			$tentativa++;

			$msg = "[".date("d-m-Y H:i:s")."] ERRO: Faltam arquivos, essa foi a ".$tentativa."ª tentativa...\n";
			fwrite($fp, $msg);
			
			if($tentativa < 3){
				sleep(10); // Tempo de espera entre cada tentativa
			}
			$nome_arquivos = [];
		}
	}while(count($nome_arquivos) < 3 && $tentativa < 3);

	// Caso não ache arquivos para a execução e manda um email
	if(empty($nome_arquivos)){
		$msg = "[".date("d-m-Y H:i:s")."] ERRO: Faltam arquivos, parando a execução do script...\n";
		fwrite($fp, $msg);
		fnMail("ERRO CÓPIA ARQUIVOS", "Um erro ocorreu no script de cópia de arquivos, consulte o arquivo de log para mais detalhes.", $emails);
		exit;
	}

	// Iniciar processos
	for($i=0; $i<count($nome_arquivos); $i++){
		$arquivo_origem = $path_origem.$nome_arquivos[$i];
		$arquivo_destino = $path_destino.$nome_arquivos[$i];

		// Copiar Arquivos
		if(!copy($arquivo_origem, $arquivo_destino)){
			$msg = "[".date("d-m-Y H:i:s")."] ERRO: Erro na cópia do arquivo: ".$nome_arquivos[$i]."\n";
			fwrite($fp, $msg);
			fnMail("ERRO CÓPIA ARQUIVOS", "Um erro ocorreu no script de cópia de arquivos, consulte o arquivo de log para mais detalhes.", $emails);
			exit;
		}

		// Descompactar Arquivos
		$zip = new ZipArchive;
		$zip->open($arquivo_destino);
			if(!$zip->extractTo($path_destino)){
				$msg = "[".date("d-m-Y H:i:s")."] ERRO: Erro ao descompactar o arquivo: ".$nome_arquivos[$i]."\n";
				fwrite($fp, $msg);
				fnMail("ERRO CÓPIA ARQUIVOS", "Um erro ocorreu no script de cópia de arquivos, consulte o arquivo de log para mais detalhes.", $emails);
				exit;
			}
		$zip->close();
		
		// Renomear Arquivos
		$nome_antigo = $path_destino.substr($nome_arquivos[$i], 0, -4).".csv"; // -4 para tirar o '.zip'
		$nome_novo = $path_destino.substr($nome_arquivos[$i], 0, -15).".csv"; // -15 para tirar o '_00-00-0000.zip'
		if(!rename($nome_antigo, $nome_novo)){
			$msg = "[".date("d-m-Y H:i:s")."] ERRO: Erro ao renomear o arquivo: ".$nome_arquivos[$i]."\n";
			fwrite($fp, $msg);
			fnMail("ERRO CÓPIA ARQUIVOS", "Um erro ocorreu no script de cópia de arquivos, consulte o arquivo de log para mais detalhes.", $emails);
			exit;
		}
	}

	fclose($fp);
?>