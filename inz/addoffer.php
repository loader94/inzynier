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
	if (isset($_POST['product_name']))
	{
		$validation=true;
		
		//kategoria
		$category = $_POST['category'];
		if($category=='none' || (!isset($_POST['category'])))
		{
			$validation=false;
			$_SESSION['error_category']='<div class="reg_error">Proszę wybrać kategorię produktu</div>';
		}
		//producent telefon
		if($category=='telefony')
		{
			$producer=$_POST['phones'];
			if((!isset($_POST['phones'])) || $producer=='none')
			{
				$validation=false;
				$_SESSION['error_producer']='<div class="reg_error">Proszę wybrać producenta telefonu</div>';
			}
		}
		
		//productent laptop
		if($category=='laptopy')
		{
			$producer=$_POST['laps'];
			if((!isset($_POST['laps'])) || $producer=='none')
			{
				$validation=false;
				$_SESSION['error_producer']='<div class="reg_error">Proszę wybrać producenta telefonu</div>';
			}
		}
		//nazwa produktu
		$prod_name = $_POST['product_name'];
		$prod_name = htmlentities($prod_name, ENT_QUOTES, "UTF-8");
		if($prod_name == "")
		{
			$validation = false;
			$_SESSION['error_prod']='<div class="reg_error">Proszę podać opis</div>';
		}
		//opis
		
		$desc = nl2br($_POST['description']);
		if($desc == "")
		{
			$validation = false;
			$_SESSION['error_desc']='<div class="reg_error">Proszę podać opis</div>';
		}
		
		$price = $_POST['price'];
		$price = str_replace( ',', '.', $price);
		
		if(!is_numeric($price))
		{
			$validation = false;
			$_SESSION['error_price']='<div class="reg_error">Proszę podać właściwą cenę</div>';
		}
		$price = floatval($price);
		
		//obraz
		$image = "uploads/";
		
		if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) 
		{
			$validation=false;
			$_SESSION['error_img']='<div class="reg_error">Wybierz zdjęcie</div>';
		}
		else
		{
			$image =$image.basename($_FILES['image']['tmp_name']);
			move_uploaded_file($_FILES['image']['tmp_name'], $image);
		}
		
		//recaptcha
		$secret = "6LfYiwsUAAAAAG2yzf5toC3YuAnGAnV0a6B3-uBh";
		
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);//odp w formie jsona
		
		$resp = json_decode($check);//odpowiedz w formie jsona dekodujemy
		
		if ($resp->success==false)
		{
			$validation=false;
			$_SESSION['error_recaptcha']='<div class="reg_error">Potwierdź, że nie jesteś robotem!</div>';
		}		
		
		
		//zapamietanie wprowadzonych informacji
		//todo
		
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
				if ($validation==true)//jesli przeszlismy walidacje to dodaj oferte uzytkownika do bazy
				{
					if ($conn->query("INSERT INTO products VALUES (NULL, '{$_SESSION['id_user']}', '$category', '$producer', '$prod_name', '$desc', $price, '$image', 0)"))
					{
						$_SESSION['product_upload_success']=true;
						header('Location: hi.php');
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
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie oferty!</span>';
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
		<script src="jquery-3.1.1.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
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

		
		<script src="hideshow.js"></script>
		<div class="offerpanel">
			<div class="tag">
					Dodawanie oferty
			</div>
			<form method="post" enctype="multipart/form-data">
				<select id="cat" name="category">
					<option value="none">Wybierz kategorie</option>
					<option value="telefony">Telefon</option>
					<option value="laptopy">Laptop</option>
				</select>
				
				<?php //jak blad to komunikat
							if(isset($_SESSION['error_category']))
							{
								echo $_SESSION['error_category'];
								unset($_SESSION['error_category']);
							}
				?>	
				
				<select id="pho" name="phones">
					<option value="none">Wybierz producenta</option>
					<option value="Alcatel">Alcatel</option>
					<option value="Apple">Apple</option>
					<option value="HTC">HTC</option>
					<option value="Huawei">Huawei</option>
					<option value="Lenovo">Lenovo</option>
					<option value="LG">LG</option>
					<option value="Samsung">Samsung</option>
					<option value="Sony">Sony</option>
					<option value="Xiaomi">Xiaomi</option>
					<option value="Inne">Inne</option>
				</select>
				
				<select id="lap" name="laps">
					<option value="none">Wybierz producenta</option>
					<option value="Acer">Acer</option>
					<option value="Asus">Asus</option>
					<option value="Dell">Dell</option>
					<option value="HP">HP</option>
					<option value="Lenovo">Lenovo</option>
					<option value="MSI">MSI</option>
					<option value="Samsung">Samsung</option>
					<option value="Sony">Sony</option>
					<option value="Toshiba">Toshiba</option>
					<option value="Inne">Inne</option>
				</select>
				
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_producer']))
					{
						echo $_SESSION['error_producer'];
						unset($_SESSION['error_producer']);
					}
				?>	
				
				<input type="text" name="product_name" placeholder="Nazwa urządzenia" onfocus="this.placeholder=''" onblur="this.placeholder='Nazwa urządzenia'" />
				
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_prod']))
					{
						echo $_SESSION['error_prod'];
						unset($_SESSION['error_prod']);
					}
				?>	
				
				<textarea name="description" cols="40" rows="5" placeholder="Opis urządzenia" onfocus="this.placeholder=''" onblur="this.placeholder='Opis urządzenia'" ></textarea>
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_desc']))
					{
						echo $_SESSION['error_desc'];
						unset($_SESSION['error_desc']);
					}
				?>	
				
				<input type="number" step="0.01" name="price" placeholder="Cena (np. 19.99)" onfocus="this.placeholder=''" onblur="this.placeholder='Cena (np. 19.99)'">
					
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_price']))
					{
						echo $_SESSION['error_price'];
						unset($_SESSION['error_price']);
					}
				?>	
				<label name="file">	
					<input type="file" name="image" class="inputfile"/>
					Dodaj zdjęcie
				</label>
				
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_img']))
					{
						echo $_SESSION['error_img'];
						unset($_SESSION['error_img']);
					}
				?>	
				
				<div class="g-recaptcha" data-sitekey="6LfYiwsUAAAAAIYhfYqryNN4K7NkRh4rcKirQXf6"></div>
					
				<?php //jak blad to komunikat
					if(isset($_SESSION['error_recaptcha']))
					{
						echo $_SESSION['error_recaptcha'];
						unset($_SESSION['error_recaptcha']);
					}
				?>
					
					<input type="submit" name = "reg" value="Dodaj ofertę" />
					
				</form>
		</div>
		
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>
		<script src="stickyMenu.js"></script>

	</body>
</html>