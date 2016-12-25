<?php
	require_once "conn.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	$conn = new mysqli($db_adress, $db_user, $db_pass, $db_name);
	$query = "SELECT products.*, users.email, users.phone, users.name as user_name FROM products, users where products.id_user=users.id_user and products.is_accepted=1";
	if(isset($_GET['category']))
	{
		$query=$query." and products.category='{$_GET['category']}'";
	}
	if(isset($_POST['search']))
	{
		$query=$query." and products.name like '%{$_POST['search']}%'";
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