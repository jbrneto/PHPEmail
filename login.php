<?php
	$login = trim($_POST['login']);
	$pass = trim($_POST['pass']);

	$mysqli = mysqli_connect("localhost","root","","mysql") or die("Connection Failed: ".mysqli_connect_errno."-".mysqli_connect_error);
	$stm = $mysqli->prepare("SELECT * FROM Usuarios WHERE login_usuario=? AND senha_usuario=?") or die("Prepare Failed: ".mysqli_errno."-".mysqli_error);
	$stm->bind_param("ss",$login,$pass) or die("Bind Param Failed: ".$stm->errno." . ".$stm->error);
	$stm->execute() or die("Select Failed: ".$stm->errno." . ".$stm->error);
	$stm->bind_result($id,$nome,$login,$email,$senha) or die("Bind Result Failed: ".$stm->errno." . ".$stm->error);
	if($stm->fetch()){
		$json_obj = array(
			'id' => $id,
			'nome'=> $nome,
			'login'=> $login,
			'email'=> $email
		);
		$json_str = json_encode($json_obj);
		session_start();
		$_SESSION['user'] = $json_str;
		echo "loginAuth";
	}else{
		$log = array(
			"log" => "Login/Email or Password are invalid!",
			"logStatus" => "error"
		);
		$json_str = json_encode($log);
		echo $json_str;
	}
	$stm->close();
	$mysqli->close();
?>