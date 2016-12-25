<?php
	session_start();
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))//jak sie zalogowano przejdz do serwisu
	{
		header('Location: service.php');
		exit();
	}
	if(!isset($_SESSION['register_success']))
	{
		header('Location: index.php');
		exit();
	}
	if(isset($_SESSION['err']))
			unset($_SESSION['err']);
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
				Dziękujemy za rejestację w naszym serwisie! Możesz się już zalogować na swoje nowe konto!
			</div>
			
			<div class="loginpanel">
				<div class="tag">
					Zaloguj się do serwisu!
				</div>
				<?php
					if(isset($_SESSION['err']))	
						echo $_SESSION['err'];
				?>
				<form action="login.php" method="post">
	
					<input type="text" value="<?php
							if(isset($_SESSION['saved_login']))
							{
								echo $_SESSION['saved_login'];
								unset($_SESSION['saved_login']);
							}
					?>" name="login" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'"  /> 
					<input type="password" value="<?php
							if(isset($_SESSION['saved_pass1']))
							{
								echo $_SESSION['saved_pass1'];
								unset($_SESSION['saved_pass1']);
							}
					?>" name="passw" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'"  />
					<input type="submit" name="log" value="Zaloguj się" />
	
				</form>
				
			</div>
		</div>
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>

	</body>
</html>