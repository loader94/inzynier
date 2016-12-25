<?php
	$response = array();
	$response['success']=true;
	$login = $_POST['login'];
	$pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	
	if (ctype_alnum($login)==false)//tylko znaki alfanumeryczne
	{
		$response['success']=false;
		$response['error']="Login musi składać się ze znaków alfanumerycznych";
	}
	
	if ((strlen($login)<4) || (strlen($login)>16))//dolugosc od 4 do 16 znakow
	{
		$response['success']=false;
		$response['error']="Login musi mieć od 4 do 16 znaków";
	}
	
	if (strlen($pass1)<4)//conajmniej 4 znaki
	{
		$response['success']=false;
		$response['error']="Hasło musi mieć conajmninej 4 znaki";
	}
	
	if ($pass1!=$pass2)//rozne hasla
	{
		$response['success']=false;
		$response['error']="Hasła nie są identyczne";
	}	

	$hash_pass = password_hash($pass1, PASSWORD_DEFAULT);//haszowanie

	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);//pozbywanie sie blednych znakow z maila
	
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))//poprawnosc maila
	{
		$response['success']=false;
		$response['error']="Błędny email";
	}
	
	if(strlen($phone)!=9)
	{
		$response['success']=false;
		$response['error']="Numer telefonu musi się składać z 9 cyfr";
	}
	
	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	

	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);

	$result = $conn->query("SELECT id_user FROM users WHERE email='$email'");
	
	$mails = $result->num_rows;
	if($mails>0)//jak istnieje mail to lipa
	{
		$response['success']=false;
		$response['error']="Istnieje już taki email";
	}		

	//Czy login istnieje
	$result = $conn->query("SELECT id_user FROM users WHERE name='$login'");
	
	$logins = $result->num_rows;
	if($logins>0)
	{
		$response['success']=false;
		$response['error']="Istnieje już taki login";
	}
	
	if ($response['success']==true)//jesli przeszlismy walidacje to dodaj uzytkownika do bazy
	{
		if ($conn->query("INSERT INTO users VALUES (NULL, 0, 0, 0, '$login', '$hash_pass', '$email', '$phone', CURDATE())"))
		{
			$response['success']=true;
		}		
		else{
			$response['success']=false;
			$response['error']="Błąd bazy danych";
		}
	}
	$conn->close();
	echo json_encode($response);
	
	
?>