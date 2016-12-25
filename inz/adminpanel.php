<?php
	session_start();
	if(!isset($_SESSION['logged']))
	{
		header("Location: index.php");
		exit();
	}
	if($_SESSION['is_admin']==0)
	{
		header("Location: service.php");
		exit();
	}
	
	if(isset($_GET['makemode']) && isset($_GET['id_user']))
	{			
		if(($_GET['makemode']==0))
		{
			require_once "conn.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			try 
			{
				$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
				if ($conn->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$upd_string = "UPDATE users set is_mode=0 where id_user=".$_GET['id_user']."";
					$wynik = $conn->query($upd_string);						
					$conn->close();
					header("Location: adminpanel.php");
					exit();
				}

			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
		}
		if(($_GET['makemode']==1))
		{
			require_once "conn.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			try 
			{
				$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
				if ($conn->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$upd_string = "UPDATE users set is_mode=1 where id_user=".$_GET['id_user']."";
					$wynik = $conn->query($upd_string);						
					$conn->close();
					header("Location: adminpanel.php");
					exit();
				}

			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
		}
	}
	if(isset($_GET['makeban']) && isset($_GET['id_user']))
	{
		if(($_GET['makeban']==0))
		{
			require_once "conn.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			try 
			{
				$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
				if ($conn->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$upd_string = "UPDATE users set is_banned=0 where id_user=".$_GET['id_user']."";
					$wynik = $conn->query($upd_string);						
					$conn->close();
					header("Location: adminpanel.php");
					exit();
				}

			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
		}
		if(($_GET['makeban']==1))
		{
			require_once "conn.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			try 
			{
				$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
				if ($conn->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$upd_string = "UPDATE users set is_banned=1 where id_user=".$_GET['id_user']."";
					$wynik = $conn->query($upd_string);						
					$conn->close();
					header("Location: adminpanel.php");
					exit();
				}

			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
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
		<div class="content"><?php
			require_once "conn.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
			
			try 
			{
				$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
				if ($conn->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				else
				{
					$search_string = "SELECT * from users where is_admin=0";
					$wynik = $conn->query($search_string);
					
					while ($AFR = mysqli_fetch_assoc($wynik))
					{					
						echo '<div class="user">ID użytkownika: '.$AFR['id_user'].'<br />Nazwa użytkownika: '.$AFR['name'].'<br />Email: '.$AFR['email'].'<br />Telefon: '.$AFR['phone'].'<br />Data dołączenia: '.$AFR['date'].'<br />Czy jest moderatorem: '.$AFR['is_mode'].'<br />Czy jest zbanowany: '.$AFR['is_banned'];
						if($AFR['is_mode']==0)
						{
							echo '<a href="?makemode=1&id_user='.$AFR['id_user'].'">Mianuj moderatorem</a>';
						}
						if($AFR['is_mode']==1)
						{
							echo '<a href="?makemode=0&id_user='.$AFR['id_user'].'">Zdejmij moderatora</a>';
						}
						if($AFR['is_banned']==0)
						{
							echo '<a href="?makeban=1&id_user='.$AFR['id_user'].'">Zbanuj</a></div>';
						}
						if($AFR['is_banned']==1)
						{
							echo '<a href="?makeban=0&id_user='.$AFR['id_user'].'">Odbanuj</a></div>';
						}
					}
										
					$conn->close();
				}
				
			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności!</span>';
				echo '<br />Informacja developerska: '.$e;
			}
		?>
		</div>
		<script src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>
		<script src="stickyMenu.js"></script>

	</body>
</html>