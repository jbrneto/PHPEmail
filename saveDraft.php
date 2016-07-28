<?php
	session_start();
	$idUser = $_SESSION['idUser'];
	$destination = trim($_POST['destination']);
	$subject = trim($_POST['subject']);
	$msg = trim($_POST['message']);

	$mysqli = mysqli_connect("localhost","root","","mysql") or die("Connect Fail: ".mysqli_connect_errno." - ".mysqli_connect_error);
	$idRemetete = 'NULL';
	if(!empty($destination)){
		//Verifica se o email é de algum usuário e caso sim pega o ID deste
		$stm = $mysqli->prepare("SELECT id_usuario FROM Usuarios WHERE email_usuario=?") or die("Prepare Failed: ".$mysqli->errno." - ".$mysqli->error);
		$stm->bind_param("s",$destination) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
		$stm->execute() or die("Execute Failed: ".$stm->errno." . ".$stm->error);
		$stm->bind_result($id) or die("Bind Result Failed: ".$stm->errno." . ".$stm->error);
		
		if($stm->fetch()){
			$idRemetete = $id;
		}
		$stm->close();
	}


	/*
	Caso seja o primiero rascunho
	*/
	if(!(isset($_POST['rascunho']))){
		
		
		//Cria um e-mail com ou sem destinatário
		$stm = $mysqli->prepare("INSERT INTO Emails 
			(id_usuario_emitente, id_usuario_remetente, data_email, assunto_email, msg_email, lido_email) 
			VALUES ($idUser,$idRemetete,NOW(),?,?,0)") or die("Prepare Failed: ".$mysqli->errno."-".$mysqli->error);
		$stm->bind_param("ss",$subject,$msg) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
		
		//Query que seleciona o ID do INSERT acima (último email entre o usuario da sessão e o usuário de destino, ou NULL se ainda naõ houver destino)
		$querySelectLastId = "SELECT id_email FROM Emails WHERE id_usuario_emitente = $idUser 
		AND (id_usuario_remetente = $idRemetete OR id_usuario_remetente IS NULL) ORDER BY data_email DESC";
		
		//Evento de INSERT e SELECT bem próximos para evitar problemas
		$stm->execute() or die("Execute Failed: ".$stm->errno." - ".$stm->error);
		$rs = mysqli_query($mysqli, $querySelectLastId);
		if(!$rs){
			$log = array(
				"log" => "Failed to create the the draft!",
				"logStatus" => "error"
			);
			$json_str = json_encode($log);
			echo $json_str;
			die();
		}
		$row = mysqli_fetch_assoc($rs);
		$stm->close();
		
		//Cria um Action para Rascunho que referencia o e-mail
		$queryInsertAction = "INSERT INTO ActionEmail (id_usuario_action, id_email_action, cod_action) VALUES ($idUser,".$row['id_email'].", 2)";
		if(!($mysqli->query($queryInsertAction))){
			$log = array(
				"log" => "Failed to save the the draft!",
				"logStatus" => "error"
			);
			$json_str = json_encode($log);
			echo $json_str;
			die();
		}
		$log = array(
			"log" => "Draft was been created!",
			"logStatus" => "success"
		);
		$json_str = json_encode($log);
		echo $json_str;
		
		
	/*
		Caso o rascunho ja exista e ele quer salver esse (sobrescrever) o antigo
	*/	
	}else{
		$idRascunho = $_POST['rascunho'];
		$stm = $mysqli->prepare("
		UPDATE Emails SET id_usuario_remetente=$idRemetete, data_email=NOW(), assunto_email=?, msg_email=? WHERE id_email=$idRascunho") 
			or die("Prepare Failed: ".$mysqli->errno."-".$mysqli->error);
		$stm->bind_param("ss",$subject,$msg) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
		if($stm->execute()){
			$log = array(
				"log" => "Draft was been saved!",
				"logStatus" => "success"
			);
			$json_str = json_encode($log);
			echo $json_str;
		}else{
			$log = array(
				"log" => "Fault trying save draft!",
				"logStatus" => "error"
			);
			$json_str = json_encode($log);
			echo $json_str;
		}
	}
	$mysqli->close();
?>