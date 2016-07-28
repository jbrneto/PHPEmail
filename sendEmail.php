<?php
	session_start();
	$idUser = $_SESSION['idUser'];
	$destination = trim($_POST['destination']);
	$subject = trim($_POST['subject']);
	$msg = trim($_POST['message']);

	$mysqli = mysqli_connect("localhost","root","","mysql") or die("Connect Fail: ".mysqli_connect_errno);
	//Verifica se o email é de algum usuário e caso sim pega o ID deste
	$stm = $mysqli->prepare("SELECT id_usuario FROM Usuarios WHERE email_usuario=?") or die("Prepare Failed: ".mysqli_errno."-".mysqli_error);
	$stm->bind_param("s",$destination) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
	$stm->execute() or die("Execute Failed: ".$stm->errno." . ".$stm->error);
	$stm->bind_result($id) or die("Bind Result Failed: ".$stm->errno." . ".$stm->error);
	//Caso não haja um usuário com este e-mail
	if(!($stm->fetch())){
		$log = array(
			"log" => "Destination email is invalid!",
			"logStatus" => "error"
		);
		$json_str = json_encode($log);
		echo $json_str;
		die();
	}
	$idRemetete = $id;
	$stm->close();
	//Insere a mesnagem
	$stm = $mysqli->prepare("INSERT INTO Emails 
		(id_usuario_emitente, id_usuario_remetente, data_email, assunto_email, msg_email, lido_email) 
		VALUES ($idUser,$idRemetete,NOW(),?,?,0)") or die("Prepare Failed: ".mysqli_errno."-".mysqli_error);
	$stm->bind_param("ss",$subject,$msg) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
	$stm->execute() or die("Execute Failed: ".$stm->errno." . ".$stm->error);
	$stm->close();
	//Caso o email tenha sido um rascunho o rascunho deve ser apagado do ActionEmail e dos Emails
	if(isset($_POST['rascunho'])){
		$idRascunho = $_POST['rascunho'];
		$mysqli->query("DELETE FROM ActionEmail WHERE id_email_action = $idRascunho AND id_usuario_action = $idUser AND cod_action = 2")
			or die("Delete From ActionEmail Fail: ".$mysqli->error);
		$mysqli->query("DELETE FROM Emails WHERE id_email = $idRascunho") or die("Delete From Emails Fail: ".$mysqli->error);
	}
	$log = array(
			"log" => "Email was sent with success!",
			"logStatus" => "success"
		);
	$json_str = json_encode($log);
	echo $json_str;
	$mysqli->close();
?>