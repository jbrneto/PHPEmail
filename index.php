<DOCTYPE html>
<?php
	if(isset($_REQUEST['logout'])){
		session_start();
		session_destroy();
	}
?>
<html>
	<head>
		<title>PHP Email</title>
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="keywords" content="php, email, login, signup, jbrneto">
		<meta name="author" content="github.com/jbrneto">
		<link rel="stylesheet" href="css/login.css">
		<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
	</head>
	<body>
		<header>
			<div class="container">
				<h1>PHP EMAIL</h1>
			</div>
		</header>
		<div class="log">
			<div class="container">
				<p id="logIndex"></p>
				<figure id="quitLog"><img src="imgs/response.png"></figure>
			</div>
		</div>
		<section>
			<form id="loginForm" action="login.php" method="post">
				<h2>Login</h2>
				<input name="login" type="text" placeholder="Login or Email" required>
				<input name="pass" type="password" placeholder="Password" required>
				<buttongroup>
					<input type="submit" value="Login">
				</buttongroup>
			</form>
			<form id="signupForm" action="signup.php" method="post">
				<h2>Sign Up</h2>
				<input name="name" type="text" placeholder="Name" required>
				<input name="login" type="text" placeholder="Login" required>
				<input name="email" type="text" placeholder="Email Addres" required>
				<input name="pass" type="password" placeholder="Password" required>
				<input name="passConf" type="password" placeholder="Password Confirm" required>
				<buttongroup>
					<input type="submit" value="Create">
					<input type="reset" value="Clear">
				</buttongroup>
			</form>
		</section>
		<footer>
			<div class="container">
				<p>License: MIT (Open Source) - Contato: jbbatistajoao@gmail.com</p>
			</div>
		</footer>
	</body>
</html>