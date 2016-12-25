<?php

	session_start();
	if (isset($_POST['email']))
	{
		$validation=true;
		//login
		$login = $_POST['login'];
		
		if (ctype_alnum($login)==false)//tylko znaki alfanumeryczne
		{
			$validation=false;
			$_SESSION['error_login']='<div class="reg_error">Login musi składać się tylko ze znaków a-z A-Z 0-9</div>';
		}
		
		if ((strlen($login)<4) || (strlen($login)>16))//dolugosc od 4 do 16 znakow
		{
			$validation=false;
			$_SESSION['error_login']='<div class="reg_error">Login musi zawierać od 4 do 16 znaków</div>';
		}
		
		//haslo
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
		if (strlen($pass1)<4)//conajmniej 4 znaki
		{
			$validation=false;
			$_SESSION['error_pass']='<div class="reg_error">Hasło musi posiadać conajmniej 4 znaki</div>';
		}
		
		if ($pass1!=$pass2)//rozne hasla
		{
			$validation=false;
			$_SESSION['error_pass']='<div class="reg_error">Podane hasła nie są takie same</div>';
		}	

		$hash_pass = password_hash($pass1, PASSWORD_DEFAULT);//haszowanie
		
		//email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);//pozbywanie sie blednych znakow z maila
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))//poprawnosc maila
		{
			$validation=false;
			$_SESSION['error_email']='<div class="reg_error">Podano błędny adres email</div>';
		}
		
		//telefon
		$phone_num=strval($_POST['phone']);
		
		if(strlen($phone_num)!=9)
		{
			$validation=false;
			$_SESSION['error_phone']='<div class="reg_error">Numer telefonu musi się składać z 9 cyfr</div>';
		}
		
		//regulamin
		if (!isset($_POST['terms']))//checkbox set albo nie
		{
			$validation=false;
			$_SESSION['error_terms']='<div class="reg_error">Potwierdź akceptację regulaminu</div>';
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
		$_SESSION['saved_login'] = $login;
		$_SESSION['saved_email'] = $email;
		$_SESSION['saved_pass1'] = $pass1;
		$_SESSION['saved_pass2'] = $pass2;
		$_SESSION['saved_phone'] = $phone_num;
		
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
				//Czy email istnieje w bazie
				$result = $conn->query("SELECT id_user FROM users WHERE email='$email'");
				
				if (!$result) throw new Exception($conn->error);
				
				$mails = $result->num_rows;
				if($mails>0)//jak istnieje mail to lipa
				{
					$validation=false;
					$_SESSION['error_email']='<div class="reg_error">Taki email już istnieje w bazie</div>';
				}		

				//Czy login istnieje
				$result = $conn->query("SELECT id_user FROM users WHERE name='$login'");
				
				if (!$result) throw new Exception($conn->error);
				
				$logins = $result->num_rows;
				if($logins>0)
				{
					$validation=false;
					$_SESSION['error_login']='<div class="reg_error">Istnieje już użytkownik o podanym loginie</div>';
				}
				
				if ($validation==true)//jesli przeszlismy walidacje to dodaj uzytkownika do bazy
				{
					if ($conn->query("INSERT INTO users VALUES (NULL, 0, 0, 0, '$login', '$hash_pass', '$email', '$phone_num', CURDATE())"))
					{
						$_SESSION['register_success']=true;
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
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
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
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>

	<body>
		<div class="wrapper">
			<div class = "header">
				<div class = "logo">
					Serwis handlowy laptopów i telefonów
				</div>
			</div>
			<div class="registerpanel">
				<div class="tag">
					Rejestracja
				</div>
				<form method="post">
					<input type="text" value="<?php
							if(isset($_SESSION['saved_login']))
							{
								echo $_SESSION['saved_login'];
							}
					?>" name="login" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'" />
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_login']))
							{
								echo $_SESSION['error_login'];
								unset($_SESSION['error_login']);
							}
					?>	
					
					<input type="password" value="<?php
							if(isset($_SESSION['saved_pass1']))
							{
								echo $_SESSION['saved_pass1'];
							}
					?>" name="pass1" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'"  />
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_pass']))
							{
								echo $_SESSION['error_pass'];
								unset($_SESSION['error_pass']);
							}
					?>	
					
					<input type="password" value="<?php
							if(isset($_SESSION['saved_pass2']))
							{
								echo $_SESSION['saved_pass2'];
								unset($_SESSION['saved_pass2']);
							}
					?>" name="pass2" placeholder="Powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Powtórz hasło'"  />
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_pass']))
							{
								echo $_SESSION['error_pass'];
								unset($_SESSION['error_pass']);
							}
					?>
					
					<input type="text" value="<?php
							if(isset($_SESSION['saved_email']))
							{
								echo $_SESSION['saved_email'];
								unset($_SESSION['saved_email']);
							}
					?>" name="email" placeholder="Email" onfocus="this.placeholder=''" onblur="this.placeholder='Email'"  /> 
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_email']))
							{
								echo $_SESSION['error_email'];
								unset($_SESSION['error_email']);
							}
					?>
					
					<input type="number" value="<?php
							if(isset($_SESSION['saved_phone']))
							{
								echo $_SESSION['saved_phone'];
								unset($_SESSION['saved_phone']);
							}
					?>" name="phone" placeholder="Numer telefonu" onfocus="this.placeholder=''" onblur="this.placeholder='Numer telefonu'"  /> 
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_phone']))
							{
								echo $_SESSION['error_phone'];
								unset($_SESSION['error_phone']);
							}
					?>
					
					<label name="regulamin">
						<input type="checkbox"  name="terms" id="check_reg"/>
						Akceptuję regulamin
					</label>
					
					<?php //jak blad to komunikat
							if(isset($_SESSION['error_terms']))
							{
								echo $_SESSION['error_terms'];
								unset($_SESSION['error_terms']);
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
					
					<input type="submit" name = "reg" value="Zarejestruj się" />
					
				</form>
			</div>
		</div>
		<script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js"></script>

	</body>
</html>