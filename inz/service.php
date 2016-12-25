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
	if((isset($_GET['prod_id'])) && (isset($_GET['delete'])))
	{
		if($_SESSION['is_mode']==0)
		{
			header("Location: service.php");
			exit();
		}
		else
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
					$del_string = "DELETE FROM products where id_prod=".$_GET['prod_id']."";
					$wynik = $conn->query($del_string);						
					$conn->close();
					header("Location: service.php");
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
		<div class="search">
			<form action="service.php" method="post">
				<input type="text" name="search" placeholder="Wyszukaj" onfocus="this.placeholder=''" onblur="this.placeholder='Wyszukaj'"/>
				<input type="submit" name = "reg" value="Szukaj" />
			</form>	
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
					
					$na_stronie = 5;
					$search_string = "SELECT products.*, users.email, users.phone, users.name as name_user FROM products, users where products.is_accepted=1 and products.id_user=users.id_user";
					if(isset($_POST['search']))
					{
						$search_string = $search_string." and products.name like '%{$_POST['search']}%'";
					}
					if(isset($_GET['category']))
					{
						$search_string = $search_string." and category='{$_GET['category']}'";
					}
					if(isset($_GET['producer']))
					{
						$search_string = $search_string." and producer='{$_GET['producer']}'";
					}
					//echo $search_string;
					//echo $search_string;
					$res = $conn->query($search_string);
					$liczba_wpisow = mysqli_num_rows($res);

					$liczba_stron = ceil($liczba_wpisow/$na_stronie);
					if (isset($_GET['strona'])) 
					{
						if ($_GET['strona'] < 1 || $_GET['strona'] > $liczba_stron) $strona = 1;
						else $strona = $_GET['strona'];
					}
					else $strona = 1;
					
					$od = $na_stronie * ($strona - 1);

					$zapytanie = $search_string." ORDER BY products.id_prod DESC LIMIT $od , $na_stronie";

					$wynik = $conn->query($zapytanie);
					
					while ($AFR = mysqli_fetch_assoc($wynik))
					{			
						echo '<div class="item"><div class="item_name">'.$AFR['name'].'</div><div class="item_price">'.$AFR['price'].'zł</div><div style="clear:both"></div><div class="item_cat">Kategoria: '.$AFR['category'].'</div><div class="item_prod">Producent: '.$AFR['producer'].'</div><div style="clear:both"></div><div class="item_photo"><img src="'.$AFR['photopath'].' " /></div><div class="item_desc">'.$AFR['description'].'</div><div class="item_contact">Oferta uzytkownika '.$AFR['name_user'].'<br />Kontakt: Email '.$AFR['email'].' Telefon '.$AFR['phone'];
						if($_SESSION['is_mode']==0)
						{
							echo '<a href="message.php?user='.$AFR['name_user'].'">Napisz wiadomość</a></div></div>';
						}
						else
						{
							echo '<a href="message.php?user='.$AFR['name_user'].'">Napisz wiadomość</a><a href="?prod_id='.$AFR['id_prod'].'&delete=1" onclick="return confirm("Are you sure you want to delete?")">Usuń ofertę</a></div></div>';
						}
					}
					

					
					if ($liczba_wpisow > $na_stronie) 
					{
						$poprzednia = $strona - 1;
						$nastepna = $strona + 1;
						echo '<div class="pages">';
						if ($poprzednia > 0) 
						{
							echo '<a id="POPRZEDNIA" href="service.php?strona='.$poprzednia.'">poprzednia strona</a>';
						}
						if ($nastepna <= $liczba_stron) {
							echo '<a id="NASTEPNA" href="service.php?strona='.$nastepna.'">następna strona</a>';
						}
						echo '</div><div style="clear:both">';
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