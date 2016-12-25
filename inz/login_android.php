<?php
	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	$login = $_POST['login'];
	$pass = $_POST['passw'];	

	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);

	$login = htmlentities($login, ENT_QUOTES, "UTF-8");//zabezpieczenie przed hakowaniem mysql encje html
	
	if ($res = $conn->query(
	sprintf("SELECT * FROM users WHERE name='%s'",
	mysqli_real_escape_string($conn,$login))))//wynik zapytania & zabezpieczenia przed wstrzykiwaniem sql
	{
		$response = array();
        $response['success'] = false;
		$is_in_db = $res->num_rows;//czy istnieje wynik
		if($is_in_db>0)
		{
			$row = $res->fetch_assoc();//wrzucenie wyniku do tablicy
			if(password_verify($pass, $row['pass']))//porownanie shashowanych hasel
			{
                $response['success'] = true;
				$response['id_user'] = $row['id_user'];
				$response['is_admin'] = $row['is_admin'];
				$response['is_mode'] = $row['is_mode'];
				$response['is_banned'] = $row['is_banned'];
				$response['name'] = $row['name'];
				$response['email'] = $row['email'];
				$response['phone'] = $row['phone'];
			}
		}
	}
	$conn->close();
	echo json_encode($response);	
?>