<!DOCTYPE html>
<html lang=“pt-br”>
	<head>
		<meta charset=“utf-8”>
		<meta content=“width=device-width, initial-scale=1, maximum-scale=1” name=“viewport”>
		
		<title>Server Checker</title>
		
		<link href=“” rel=“stylesheet”>
	</head>

	<body>
		
		<table border="1px" width="400" height="100" style="text-align: center;" >
			<tr>
				<th>SERVER</th>
				<th>STATUS</th>
			</tr>

			<?php
				$name_hosts = ["Server 01", "Server 02", "Server 03"];
				$ip_hosts = ["google.com", "teste.com", "127.0.0.1"];

				for ($i=0; $i < count($name_hosts); $i++) { 
					print "
						<tr>
							<td> $name_hosts[$i] </td>
							<td>";
								exec("ping -n 1 -w 1 " . $ip_hosts[$i], $saida, $retorno);
								
								// $saida = shell_exec("ping " . $ip_hosts[$i]);
								// print_r("<pre>");
								// print_r($saida);
								// exit;

								echo !$retorno ? "On" : "Off";
					print	"</td>
						</tr>";
				}
			?>

		</table>

		<?php
			echo date('H:i:s');
			/*sleep(30);
			header("Refresh:1");*/
		?>

	</body>
	<script src=“”></script>
</html>