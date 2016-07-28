<?php
	session_start();
	$idUser = $_SESSION['idUser'];
	$idEmailsArray = $_POST['idEmail'];
	$codAction = $_POST['codAction'];
	if(count($idEmailsArray) > 0){
		$op;
		//Verifica se o códgigo da ação é válido
		switch($codAction){
			case 1:
				$op = "Archived";
				break;
			case 3:
				$op = "marked as SPAM";
				break;
			case 4:
				$op = "Excluded";
				break;
			default:
				$log = array(
					"log" => "Invalid operation!",
					"logStatus" => "error"
				);
				$json_str = json_encode($log);
				echo $json_str;
				die();
		}
		
		$mysqli = mysqli_connect("localhost","root","","mysql");
		foreach($idEmailsArray as $idEmail){
			//Caso seja para Excluir/SPAM, todas as outras referências além da de exclusão/SPAM assossiadas ao id do email devem ser apagadas
			if($codAction != 1){
				//cod_action < 3 para apagar as referências 1 e 2 (arquivado e rascunho)
				$mysqli->query("DELETE FROM ActionEmail WHERE id_email_action = $idEmail AND cod_action < 3") 
					or die("Delete From ActionEmail Fail: ".$mysqli->error);
			}
			//INSERT uma referência para cada e-mail em ActionEmail com o código da referência
			$mysqli->query("INSERT INTO ActionEmail (id_usuario_action, id_email_action, cod_action) VALUES ($idUser,$idEmail,$codAction)")
				or die("Insert into ActionEmail Fail: ".$mysqli->error);
		}
		$mysqli->close();
		$log = array(
			"log" => count($idEmailsArray)." emails were $op",
			"logStatus" => "success"
		);
		$json_str = json_encode($log);
		echo $json_str;
	}else{
		$log = array(
			"log" => "You must select a Email before!",
			"logStatus" => "error"
		);
		$json_str = json_encode($log);
		echo $json_str;
	}
?>