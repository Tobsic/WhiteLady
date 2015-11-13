<?php
	session_start();
	include("constants.php");

	//

	//

	$poiId = $_GET['poiId'];

	$url = $apiURL."Media/".$_GET['mediaId'];
	if (isset($_SESSION['username']) && isset($_SESSION['password'])){
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
	} else  {
		$username = $_GET['username'];
		$password = $_GET['password'];
	}
	$login_data = array('username' => $username,
											'password' => $password);
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'DELETE',
			'content' => http_build_query($login_data),
		),
	);


	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	$json = json_decode($result,true);

	if($result === "1"){
		$redirect_url = "./medialist.php?poiId=".$poiId;
	
		header('Location: '.$redirect_url);
	}

?>
