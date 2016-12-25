<?php
	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	$username = $_POST['username'];
	$type = $_POST['type'];
	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
	
	
	if($type=="nieprzeczytane")
	{
		$query = "SELECT * FROM messages where receiver_name='$username' and receiver_read=0";
	}
	if($type=="przeczytane")
	{
		$query = "SELECT * FROM messages where receiver_name='$username' and receiver_read=1";
	}
	if($type=="wyslane")
	{
		$query = "SELECT * FROM messages where sender_name='$username'";
	}

	$response = array();
	$wynik=$conn->query($query);
	
	while($res = mysqli_fetch_assoc($wynik))
        { 
            $response[] = $res;
        }
	
	$conn->close();
	echo json_encode(array("result" => $response));	
?>