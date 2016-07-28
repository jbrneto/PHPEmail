<?php
	session_start();
	$idUser = $_SESSION['idUser'];
	$op = $_POST['operation'];
	//Define qual seleção deve ser feita no banco:
	$query;
	switch($op){
		//-----INBOX-----
		case 1:
			/* No lugar do SELECT com not in (para pegar os registros não relacionados com ActionEmail), poderia ser posto o SELECT
				abaixo, que atua como a expressão (Venn): A*(B') - Tudo o que está em A e não está em B
				SELECT * FROM Emails em LEFT JOIN ActionEmail a ON em.id_email = a.id_email_action WHERE a.id_email_action IS NULL
			*/
			$query = "SELECT u.nome_usuario, e.id_email, e.assunto_email, e.data_email, e.lido_email FROM Usuarios u LEFT JOIN (
			SELECT * FROM Emails WHERE id_email not in (SELECT id_email_action FROM ActionEmail)) e 
			ON e.id_usuario_emitente = u.id_usuario WHERE e.id_usuario_remetente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
		//-----SENT-----
		case 2:
			$query = "SELECT CONCAT('To: ',u.nome_usuario) AS nome_usuario, e.id_email, e.assunto_email, e.data_email, 1 as lido_email 
			FROM Usuarios u LEFT JOIN (SELECT * FROM Emails em LEFT JOIN ActionEmail a ON em.id_email = a.id_email_action WHERE a.id_email_action IS NULL) e 
			ON e.id_usuario_remetente = u.id_usuario WHERE e.id_usuario_emitente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
		//-----ARCHIVE------
		case 3:
			$query = "SELECT u.nome_usuario, e.id_email, e.assunto_email, e.data_email, e.lido_email FROM Usuarios u LEFT JOIN (
    	SELECT * FROM Emails em INNER JOIN ActionEmail a ON em.id_email = a.id_email_action WHERE a.cod_action = 1) e 
			ON e.id_usuario_emitente = u.id_usuario WHERE e.id_usuario_remetente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
		//-----DRAFT-----
		case 4:
			$query = "SELECT CONCAT('Draft: ',u.nome_usuario) as nome_usuario, e.id_email, e.assunto_email, e.data_email, 1 as lido_email 
			FROM Usuarios u RIGHT JOIN ( SELECT * FROM Emails WHERE id_email in 
			(SELECT id_email_action FROM ActionEmail WHERE id_usuario_action = $idUser AND cod_action=2)) e
			ON u.id_usuario = e.id_usuario_remetente WHERE e.id_usuario_emitente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
		//-----SPAM-----
		case 5:
			$query = "SELECT u.nome_usuario, e.id_email, e.assunto_email, e.data_email, e.lido_email FROM Usuarios u LEFT JOIN (
    	SELECT * FROM Emails em INNER JOIN ActionEmail a ON em.id_email = a.id_email_action WHERE a.cod_action = 3) e 
			ON e.id_usuario_emitente = u.id_usuario WHERE e.id_usuario_remetente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
		//-----TRASH-----
		case 6:
			$query = "SELECT u.nome_usuario, e.id_email, e.assunto_email, e.data_email, e.lido_email FROM Usuarios u LEFT JOIN (
    	SELECT * FROM Emails em INNER JOIN ActionEmail a ON em.id_email = a.id_email_action WHERE a.cod_action = 4) e 
			ON e.id_usuario_emitente = u.id_usuario WHERE e.id_usuario_remetente = $idUser ORDER BY e.data_email DESC LIMIT 50";
			break;
	}
	//Exeecuta a query e monta os emails
	$mysqli = mysqli_connect("localhost","root","","mysql") or die("Connect Fail: ".mysqli_connect_errno);
	$rs = $mysqli->query($query);
	if($rs->num_rows > 0){
		$classe;
		if($op == 4){
			$classe = "draft";
		}else $classe = "show";
			
		while($row = $rs->fetch_assoc()){
			if($row['lido_email']){
				echo "<tr id='".$row['id_email']."'>
					<td><p><input type='checkbox'></p></td>
					<td class='$classe'><p>".$row['nome_usuario']."</p></td>
					<td class='$classe'><p>".$row['assunto_email']."</p></td>
					<td class='$classe'><p>".$row['data_email']."</p></td>
					</tr>";
			}else{
				echo "<tr id='".$row['id_email']."'>
					<td><p><input type='checkbox'></p></td>
					<td class='$classe'><p><strong>".$row['nome_usuario']."</strong></p></td>
					<td class='$classe'><p><strong>".$row['assunto_email']."</strong></p></td>
					<td class='$classe'><p><strong>".$row['data_email']."</strong></p></td>
					</tr>";
			}
		}
	}else{
		echo "<tr><td colspan='4'><p><strong>You have no Emails!</strong></p></td></tr>";
	}
	$mysqli->close();
?>