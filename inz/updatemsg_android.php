<?php
	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	$id = $_POST['id'];
	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
	$query = "UPDATE messages set receiver_read=1 where id_msg='$id'";
	$response['success']=false;
	if($conn->query($query))
	{
		$response['success']=true;
	}
	else
	{
		$response['success']=false;
	}

	$conn->close();
	echo json_encode($response);	
?>