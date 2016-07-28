<?php
	$nome = trim($_POST['name']);
	$email = trim($_POST['email']);
	$login = trim($_POST['login']);
	$senha = trim($_POST['pass']);
	$senhaConf = trim($_POST['passConf']);

	if($senha != $senhaConf){
		$log = array(
			"log" => "Passwords are different!",
			"logStatus" => "error"
		);
		$json_str = json_encode($log);
		echo $json_str;
		die();
	}
	$mysqli = mysqli_connect("localhost", "root", "", "mysql") or die("Conection Failed: ".mysqli_connect_errno()." - ".mysqli_connect_error());
	$stm = $mysqli->prepare("INSERT INTO Usuarios (nome_usuario, login_usuario, email_usuario, senha_usuario) VALUES (?,?,?,?)");
	$stm->bind_param("ssss",$nome,$login,$email,$senha) or die("Bind Failed: ".$stm->errno." . ".$stm->error);
	$stm->execute() or die("Execute Failed: ".$stm->errno." . ".$stm->error);
	$stm->close();
	$mysqli->close();

	$log = array(
			"log" => "Your account has been created!",
			"logStatus" => "success"
		);
		$json_str = json_encode($log);
		echo $json_str;

	/*$link = mysqli_connect('localhost', 'my_user', 'my_password', 'my_db');
	*****$results = $mysqli->query("SELECT id...")*****
	while($row = $results->fetch_assoc()){ print '<td>'.$row["id"].'</td>'; }
	while($row = $results->fetch_object()){ print '<td>'.$row->id.'</td>'; }
	if($result->num_rows > 0)

	$results = $mysqli->query("SELECT COUNT(*) FROM users");
	$get_total_rows = $results->fetch_row();
	TBM PODE SER TRATADO ASSIM:
	$results =  $statement->execute();
	if(!$results){
    print 'deu meda'; 
	}
	mysqli_close($con);
	*/
?>