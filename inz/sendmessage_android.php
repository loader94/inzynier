<?php
	$response['success']=true;
	$sender = $_POST['sender'];
	$receiver = $_POST['receiver'];
	$title = $_POST['title'];
	$msg = nl2br($_POST['msg']);

	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);

	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);

	$result = $conn->query("SELECT id_user FROM users WHERE name='$receiver'");

	$logins = $result->num_rows;
	if($logins==0)
	{
		$response['success']=false;
	}
	if ($response['success']==true)//jesli przeszlismy walidacje to dodaj uzytkownika do bazy
	{
		$conn->query("INSERT INTO messages VALUES (NULL, '$sender', '$receiver','$title','$msg',0,CURRENT_TIMESTAMP)");
	}
		
	$conn->close();
	echo json_encode($response);
	
?>