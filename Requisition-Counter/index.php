<?php
	session_start();

	// Se a sessão já existir somar 1
	if (!isset($_SESSION['count'])) {
	   	$_SESSION["count"] = 1;
	}
	else if($_SESSION["count"] <= 10) {
		$_SESSION["count"]++;
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	$ips = array();

	// Consultar IPs bloqueados
	$as = "ips.csv";
	if (file_exists($as)) {
		$arquivo = fopen($as, "r");
	    $row = 0;
	    
	    while ($line = fgetcsv($arquivo)) {
	    	if ($row++ == 0) {
				continue;
	    	}
	    	$ipsCsv[] = ['ip' => $line[0]];
		}
		fclose($arquivo);

	    // Passar as Arrow Functions de $ipsCsv para $ips
	    foreach($ipsCsv as $line){
	    	array_push($ips, $line['ip']);
	    }

	    // Comparar o array $ips com o IP atual
	    if(in_array($ip, $ips)){
	      	echo 'Bloqueado.';
	    	exit();
	    }
	  }

	// Se o contador for maior do que 10 bloquear o acesso mandando ip pro csv
	if(isset($_SESSION["count"]) and $_SESSION["count"] == 10){
		$arquivo = fopen("ips.csv", "w+");
	    $header = ['ips'];
	    fputcsv($arquivo, $header);

	    if(isset($ipsCsv)){
	    	array_push($ipsCsv, Array('ip'=> $ip));
	    	foreach ($ipsCsv as $linha) {
	        	fputcsv($arquivo, $linha);
	      	}
	    }else{
	    	$ipInicial = ['ip' => $ip];
	    	fputcsv($arquivo, $ipInicial);
	    }
	    fclose($arquivo);
	}
    
    echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	echo 'Seu IP: '.$_SERVER['REMOTE_ADDR'];
?>

<a href="apagar.php"><br>APAGAR SESSÃO</a>