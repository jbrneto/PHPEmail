<DOCTYPE html>
<?php
	session_start();
	if(isset($_SESSION['user'])){
		$json_obj = $_SESSION['user'];
		$user = json_decode($json_obj,TRUE);
		$_SESSION['idUser'] = $user['id'];
	}else{
		header("location:index.php");
		die();
	}
?>
<html>
	<head>
		<title><?=$user['nome']?></title>
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="keywords" content="php, email, jbrneto">
		<meta name="author" content="github.com/jbrneto">
		<link rel="stylesheet" href="css/email.css">
		<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
		<script type="text/javascript" src="js/email.js"></script>
	</head>
	<body class="corpoEmails">
		<nav>
				<ul>
					<div class="userInfo"><p><?=$user['nome']?></p><p><?=$user['email']?></p></div>
					<li class="btnMenu"><figure><img alt="Back Icon" src="imgs/back.png"><figcaption>Back</figcaption></figure></li>
					<li id="inboxPage"><figure><img alt="Inbox Icon" src="imgs/inbox.png"><figcaption>Inbox</figcaption></li>
					<li id="sentPage"><figure><img alt="Sent Icon" src="imgs/sent.png"><figcaption>Sent</figcaption></li>
					<li id="archivePage"><figure><img alt="Archived Icon" src="imgs/archive.png"><figcaption>Archived</figcaption></li>
					<li id="draftPage"><figure><img alt="Draft Icon" src="imgs/save.png"><figcaption>Draft</figcaption></li>
					<li id="spamPage"><figure><img alt="SPAM Icon" src="imgs/flag.png"><figcaption>SPAM</figcaption></li>
					<li id="trashPage"><figure><img alt="Trash Icon" src="imgs/trash.png"><figcaption>Trash</figcaption></li>
					<li id="logoutPage"><figure><img alt="Logout Icon" src="imgs/response.png"><figcaption>Logout</figcaption></li>
				</ul>
			</nav>
		<main>
			<div id="fundoHeader"></div>
			<div class="log">
				<div class="container">
					<p id="logEmail"></p>
					<figure class="quitLog"><img src="imgs/response.png"></figure>
				</div>
			</div>
			<header class="headerEmails">
				<ul class="container">
					<li class="btnMenu"><figure><img alt="Menu Icon" src="imgs/menu.png"><figcaption>Menu</figcaption></figure></li>
					<li id="btnNew"><figure><img alt="New Email Icon" src="imgs/plus.png"><figcaption>New Email</figcaption></figure></li>
					<editGroup class="invisible">
						<li id="btnArchive"><figure><img alt="Archive Icon" src="imgs/archive.png"><figcaption>Archive</figcaption></figure></li>
						<li id="btnSpam"><figure><img alt="Flag Spam Icon" src="imgs/flag.png"><figcaption>Flag SPAM</figcaption></figure></li>
						<li id="btnDelete"><figure><img alt="Delete Icon" src="imgs/trash.png"><figcaption>Delete</figcaption></figure></li>
					</editGroup>
					<li id="btnSearch"><figure><img alt="Search Icon" src="imgs/search.png"><figcaption>Search</figcaption></figure></li>
				</ul>
			</header>
			<aside class="container">
				<table>
					<caption>
						<h1>Inbox</h1>
						<section class="container search">
							<form action="searchEmail.php" method="post">
								<input type="text" placeholder="Search by subject, content, date or whatever" required>
								<input type="submit" value="Find">
							</form>
						</section>
					</caption>
					<colgroup>
						<col width="10%">
						<col width="30%">
						<col width="30%">
						<col width="30%">
					</colgroup>
					<thead>
						<tr>
							<th><input id="selectAll" type="checkbox"></th>
							<th>Contact</th>
							<th>Subject</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody id="emails">
						<!--<tr><td><p><input type="checkbox"></p></td><td><p>nignuem</p></td><td><p>oi</p></td><td><p>hj</p></td></tr>-->
					</tbody>
					<tfoot><tr><td colspan="4">Load More</td></tr></tfoot>
				</table>
			</aside>
			<footer>
				<div class="head">
					<h4></h4>
					<span id="closeNewEmail">X</span>
				</div>
				<div id="newEmail"></div>
			</footer>
		</main>
	</body>
</html>