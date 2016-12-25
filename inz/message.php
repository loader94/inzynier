<?php
	session_start();
	if(!isset($_SESSION['logged']))
	{
		header("Location: index.php");
		exit();
	}
	if($_SESSION['is_banned']==1)
	{
		header("Location: ban.php");
		exit();
	}
	if(isset($_POST['receiver']))
	{
		$val=true;
		$title = $_POST['title'];
		if($title=='')
		{
			$val=false;
			$_SESSION['msg_err_title']='<div class="msg_error">Podaj temat wiadomości</div>';
		}
		$msg=nl2br($_POST['msg']);
		if($msg=='')
		{
			$val=false;
			$_SESSION['msg_err_msg']='<div class="msg_error">Wpisz treść wiadomości</div>';
		}
		
		
		require_once "conn.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		$receiver = $_POST['receiver'];
		try 
		{
			$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
			if ($conn->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$result = $conn->query("SELECT id_user FROM users WHERE name='$receiver'");
				
				if (!$result) throw new Exception($conn->error);
				
				$logins = $result->num_rows;
				if($logins==0)
				{
					$val=false;
					$_SESSION['msg_err_name']='<div class="msg_error">Nie istnieje taki użytkownik</div>';
				}
				$sender=$_SESSION['name'];
				if ($val==true)//jesli przeszlismy walidacje to dodaj uzytkownika do bazy
				{
					if ($conn->query("INSERT INTO messages VALUES (NULL, '$sender', '$receiver','$title','$msg',0,CURRENT_TIMESTAMP)"))
					{
						$_SESSION['msg_success']=true;
						header('Location: messages.php');
					}
					else
					{
						throw new Exception($conn->error);
					}
					
				}
				
				$conn->close();
			}
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wysłanie wiadomości w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
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
			
		</div>

		<div class="nav">
			<ol>
				<li><a href="service.php">Strona główna</a></li>
				<li><a href="#">Mój profil</a>
					<ul>
						<li><a href="addoffer.php">Dodaj ofertę</a></li>
						<li><a href="myoffers.php">Moje oferty</a></li>
						<li><a href="messages.php">Wiadomości</a></li>
					</ul>
				</li>
				<li><a href="service.php?category=telefony">Telefony</a>
					<ul>
						<li><a href="service.php?category=telefony&producer=Alcatel">Alcatel</a></li>
						<li><a href="service.php?category=telefony&producer=Apple">Apple</a></li>
						<li><a href="service.php?category=telefony&producer=HTC">HTC</a></li>
						<li><a href="service.php?category=telefony&producer=Huawei">Huawei</a></li>
						<li><a href="service.php?category=telefony&producer=Lenovo">Lenovo</a></li>
						<li><a href="service.php?category=telefony&producer=LG">LG</a></li>
						<li><a href="service.php?category=telefony&producer=Samsung">Samsung</a></li>
						<li><a href="service.php?category=telefony&producer=Sony">Sony</a></li>
						<li><a href="service.php?category=telefony&producer=Xiaomi">Xiaomi</a></li>
						<li><a href="service.php?category=telefony&producer=Inne">Inne</a></li>
					</ul>
				</li>
				<li><a href="service.php?category=laptopy">Laptopy</a>
					<ul>
						<li><a href="service.php?category=laptopy&producer=Acer">Acer</a></li>
						<li><a href="service.php?category=laptopy&producer=Asus">Asus</a></li>
						<li><a href="service.php?category=laptopy&producer=Dell">Dell</a></li>
						<li><a href="service.php?category=laptopy&producer=HP">HP</a></li>
						<li><a href="service.php?category=laptopy&producer=Lenovo">Lenovo</a></li>
						<li><a href="service.php?category=laptopy&producer=MSI">MSI</a></li>
						<li><a href="service.php?category=laptopy&producer=Samsung">Samsung</a></li>
						<li><a href="service.php?category=laptopy&producer=Sony">Sony</a></li>
						<li><a href="service.php?category=laptopy&producer=Toshibaa">Toshibaa</a></li>
						<li><a href="service.php?category=laptopy&producer=Inne">Inne</a></li>
					</ul>
				</li>
				<li><a href="logout.php">Wyloguj się</a></li>
				<?php 
					//echo $_SESSION['is_admin'];
					//echo $_SESSION['is_mode'];
					if($_SESSION['is_mode']==1)
					{
						echo '<li><a href="modepanel.php">Panel moderatora</a></li>';
					}
					if($_SESSION['is_admin']==1)
					{
						echo '<li><a href="adminpanel.php">Panel administratora</a></li>';
					}
				?>
			</ol>
		</div>
		
		<div class="msgpanel">
			<a href="messages.php">Wszystkie</a>
			<a href="messages.php?unread">Nieprzeczytane</a>
			<a href="messages.php?read">Przeczytane</a>
			<a href="messages.php?send">Wysłane</a>
			<a href="message.php">Nowa wiadomość</a>
		</div>
		
		<div class="msg">
			<div class = "msgtag">
				Nowa wiadomość
			</div>	
				<form method="post">
					<input type="text" value="<?php if(isset($_GET['user'])) echo $_GET['user'];?>" name="receiver" placeholder="Nazwa użytkownika" onfocus="this.placeholder=''" onblur="this.placeholder='Nazwa użytkownika'" />
					<?php
						if(isset($_SESSION['msg_err_name']))
						{
							echo $_SESSION['msg_err_name'];
							unset($_SESSION['msg_err_name']);
						}
					?>
					<input type="text" name="title" placeholder="Temat" onfocus="this.placeholder=''" onblur="this.placeholder='Temat'"/>
					<?php
						if(isset($_SESSION['msg_err_title']))
						{
							echo $_SESSION['msg_err_title'];
							unset($_SESSION['msg_err_title']);
						}
					?>
					<textarea name="msg" cols="40" rows="5" placeholder="Wiadomość" onfocus="this.placeholder=''" onblur="this.placeholder='Wiadomość'" ></textarea>
					<?php
						if(isset($_SESSION['msg_err_msg']))
						{
							echo $_SESSION['msg_err_msg'];
							unset($_SESSION['msg_err_msg']);
						}
					?>
					<input type="submit" name = "send" value="Wyślij" />
				</form>
		<div>

		
		<script src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>
		<script src="stickyMenu.js"></script>

	</body>
</html>