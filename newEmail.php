<DOCTYPE html>
<html>
	<head>
			<meta name="viewport" content="width=device-width">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="keywords" content="php, email, new, jbrneto">
		<meta name="author" content="github.com/jbrneto">
		<link rel="stylesheet" href="css/newEmail.css">
		<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
	</head>
	<body class="bodyNew">
		<script type="text/javascript">
			$(document).ready(function(){
				$(".voltarNewEmail").on("click",function(){
					window.location.href = "email.php";
				});
				$(".quitLog").on('click',function(){
					$(".logMob").fadeOut("fast");
				});
				$("#saveDraft").on('click',function(){
					$.ajax({
						method:"post",
						url:"saveDraft.php",
						datatype:"json",
						data: $("#sendEmailForm").serialize(),
						success: function(data){
							if(window.innerWidth < 915){
								//MOBILE
								$(".logMob").fadeIn("fast");
								var obj_log = JSON.parse(data);
								$("#logNewEmail").html(obj_log.log);
								$(".logMob").prop("class", "logMob "+obj_log.logStatus);
								setTimeout(function(){
									location.href = "email.php";
								}, 3000);
							}else{
								//DESKTOP
								$(".log").fadeIn("fast");
								var obj_log = JSON.parse(data);
								$("#logEmail").html(obj_log.log);
								$(".log").prop("class", "log "+obj_log.logStatus);
								$("#closeNewEmail").trigger("click");
							}
						}
					});
				});
				$("#sendEmailForm").on("submit",function(){
					$.ajax({
						method: $(this).attr('method'),
						url: $(this).attr('action'),
						datatype: "json",
						data: $(this).serialize(),
						success: function(data){
							if(window.innerWidth < 915){
								//MOBILE
								$(".logMob").fadeIn("fast");
								var obj_log = JSON.parse(data);
								$("#logNewEmail").html(obj_log.log);
								$(".logMob").prop("class", "logMob "+obj_log.logStatus);
								setTimeout(function(){
									location.href = "email.php";
								}, 3000);
							}else{
								//DESKTOP
								$(".log").fadeIn("fast");
								var obj_log = JSON.parse(data);
								$("#logEmail").html(obj_log.log);
								$(".log").prop("class", "log "+obj_log.logStatus);
								$("#closeNewEmail").trigger("click");
							}
						}
					});
					return false;
				});
			});
		</script>
		<div class="corpoNewEmail">
			<header class="headerNewEmail">
				<ul>
					<li class="voltarNewEmail"><figure><img alt="Back Icon" src="imgs/back.png"><figcaption>Back</figcaption></figure></li>
					<li id="saveDraft"><figure><img alt="Save Icon" src="imgs/save.png"><figcaption>Save as Draft</figcaption></figure></li>
				</ul>
			</header>
			<div class="logMob">
				<div class="container">
					<p id="logNewEmail"></p>
					<figure class="quitLog"><img src="imgs/response.png"></figure>
				</div>
			</div>
			<main class="container mainNewEmail">
				<form id="sendEmailForm" action="sendEmail.php" method="post">
					
					<?php //Caso seja um Rascunho
					if(isset($_REQUEST['idRascunho'])){
						$id = $_REQUEST['idRascunho'];
						$mysqli = mysqli_connect("localhost","root","","mysql");
						$rs = $mysqli->query("SELECT u.email_usuario, e.assunto_email, e.msg_email FROM Emails e LEFT JOIN Usuarios u 
						ON e.id_usuario_remetente = u.id_usuario WHERE e.id_email = $id");
						$row = $rs->fetch_assoc();
						?>
						<input name="rascunho" value=<?=$id?> hidden>
						<input value="<?=$row['email_usuario']?>" name="destination" type="text" placeholder="Destination Addres" required>
						<input value="<?=$row['assunto_email']?>" name="subject" type="text" placeholder="Subject" required>
						<textarea name="message" type="text" placeholder="Type the message (maximum 255 character)"
						rows="12" required><?=$row['msg_email']?></textarea>
						<input type="submit" value="Send">
					
					
					<?php //Caso seja uma resposta
					}elseif(isset($_REQUEST['idResposta'])){ 
						$id = $_REQUEST['idResposta'];
						$mysqli = mysqli_connect("localhost","root","","mysql");
						$rs = $mysqli->query("SELECT u.email_usuario, e.assunto_email, e.msg_email FROM Emails e LEFT JOIN Usuarios u 
						ON e.id_usuario_emitente = u.id_usuario WHERE e.id_email = $id");
						$row = $rs->fetch_assoc();
					?>
						<input value="<?=$row['email_usuario']?>" name="destination" type="text" placeholder="Destination Addres" required>
						<input value=" Res: <?=$row['assunto_email']?>" name="subject" type="text" placeholder="Subject" required>
						<textarea name="message" type="text" placeholder="Type the message (maximum 255 character)" rows="12" required></textarea>
						<input type="submit" value="Send">
					
					
					<?php //Caso seja um Novo Email
					}else{ ?>
						<input name="destination" type="text" placeholder="Destination Addres" required>
						<input name="subject" type="text" placeholder="Subject" required>
						<textarea name="message" type="text" placeholder="Type the message (maximum 255 character)" rows="12" required></textarea>
						<input type="submit" value="Send">
					<?php } ?>
				</form>
			</main>	
		</div>
	</body>
</html>