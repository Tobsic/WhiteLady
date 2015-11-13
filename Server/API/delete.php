<?php
	session_start();
	include("constants.php");

	//

	//



	$url = $apiURL."poi/".$_GET['poiId'];
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
	var_dump($result);
	if($result === "1"){
		$redirect_url = "./main.php";
		header('Location: '.$redirect_url);
	}

?>
