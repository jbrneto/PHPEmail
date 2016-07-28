<DOCTYPE html>
<?php
	if(isset($_REQUEST['id_email'])){
		$idEmail = $_REQUEST['id_email'];
		$mysqli = mysqli_connect("localhost","root","","mysql");
		$rs = $mysqli->query("SELECT 
		u.email_usuario, e.assunto_email, e.msg_email, e.lido_email 
		FROM Usuarios u LEFT JOIN Emails e 
		ON e.id_usuario_emitente = u.id_usuario 
		WHERE e.id_email = $idEmail");
		$row = $rs->fetch_assoc();
		if(!($row['lido_email'])){
			$mysqli->query("UPDATE Emails SET lido_email=1 WHERE id_email= $idEmail");
		}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="keywords" content="php, email, open, jbrneto">
		<meta name="author" content="github.com/jbrneto">
		<link rel="stylesheet" href="css/openEmail.css">
		<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
	</head>
	<body class="bodyOpen">
		<script type="text/javascript">
			$(document).ready(function(){
				$(".voltar").on('click', function(){
					window.location.href = "email.php";
				});
				$("#btnResponse").on('click', function(){
					//MOBILE
					if(window.innerWidth < 915){
						window.location.href = "newEmail.php?idResposta=<?=$idEmail?>";
					}else{
						//DESKTIOP
						newEmail(<?=$idEmail?>,1);
					}
				});
			});
		</script>
		<div class="corpoOpenEmail">
			<header class="headerOpenEmail">
				<ul>
					<li class="voltar"><figure><img alt="Back Icon" src="imgs/back.png"><figcaption>Back</figcaption></figure></li>
					<li id="btnResponse"><figure><img alt="Response Email Icon" src="imgs/response.png"><figcaption>Response Email</figcaption></figure></li>
					<editGroup class="invisible">
						<li id="btnArchive"><figure><img alt="Archive Icon" src="imgs/archive.png"><figcaption>Archive</figcaption></figure></li>
						<li id="btnSpam"><figure><img alt="Flag Spam Icon" src="imgs/flag.png"><figcaption>Flag SPAM</figcaption></figure></li>
						<li id="btnDelete"><figure><img alt="Delete Icon" src="imgs/trash.png"><figcaption>Delete</figcaption></figure></li>
					</editGroup>
				</ul>
			</header>
			<main class="container mainOpenEmail">
				<input id="sender" disabled value="<?=$row['email_usuario']?>">
				<input id="subject" disabled value="<?=$row['assunto_email']?>">
				<textarea id="message"  rows="21" disabled><?=$row['msg_email']?></textarea>
			</main>	
		</div>
	</body>
</html>
<?php
	$mysqli->close();
	}
?>