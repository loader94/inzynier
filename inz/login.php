<?php
	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['passw']))) //jesli nie wpisano loginu lub hasla wroc na strone glowna
	{
		header('Location: index.php');
		exit();
	}
	
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
			$login = $_POST['login'];
			$pass = $_POST['passw'];
			//haslo admin = $2y$10$SCHMGDszaV3mFpziAF/M.O6Siv0Sbt5trSog9NhMExFrXPrxdmzim
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");//zabezpieczenie przed hakowaniem mysql encje html
			
			if ($res = $conn->query(
			sprintf("SELECT * FROM users WHERE name='%s'",
			mysqli_real_escape_string($conn,$login))))//wynik zapytania & zabezpieczenia przed wstrzykiwaniem sql
			{
				$is_in_db = $res->num_rows;//czy istnieje wynik
				if($is_in_db>0)
				{
					$row = $res->fetch_assoc();//wrzucenie wyniku do tablicy
					if(password_verify($pass, $row['pass']))//porownanie shashowanych hasel
					{
						$_SESSION['logged']=true;
						
						//todo
						//zapisywanie danych sesji uzytkownika
						$_SESSION['id_user']=$row['id_user'];
						$_SESSION['is_mode']=$row['is_mode'];
						$_SESSION['is_admin']=$row['is_admin'];
						$_SESSION['is_banned']=$row['is_banned'];
						$_SESSION['name']=$row['name'];
						
						unset($_SESSION['err']);
						$res->free_result();
						header('Location: service.php');
					}
					else
					{
						$_SESSION['err'] = '<div class="loginerr">Nieprawidłowe hasło!</div>';
						header('Location: index.php');
					}
				}
				else
				{
					$_SESSION['err'] = '<div class="loginerr">Nieprawidłowy login</div>';
					header('Location: index.php');
				}	
			}
			
			$conn->close();
		}
	}
	catch(Exception $e)
	{
		echo "Błąd połączenia";
	}
	
?>