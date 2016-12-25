<?php
	session_start();
	if (!isset($_SESSION['logged']))//jak sie zalogowano przejdz do serwisu
	{
		header('Location: index.php');
		exit();
	}
	if($_SESSION['is_banned']==0)
	{
		header('Location: service.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Praca inżynierska</title>
		<link href="https://fonts.googleapis.com/css?family=Oswald:400,700&amp;subset=latin-ext" rel="stylesheet">
		<link href="styles.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
		<div class="wrapper">
			<div class = "header">
				<div class = "logo">
					Serwis handlowy laptopów i telefonów
				</div>
			</div>
			
			<div class="welc">
				Zostałeś zbanowany!
			</div>
			<div class="loginpanel">
				<form action="logout.php" method="post">
					<input type="submit" name="log" value="Wyloguj się" />
				</form>
			</div>
		</div>
			
			
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>

	</body>
</html>