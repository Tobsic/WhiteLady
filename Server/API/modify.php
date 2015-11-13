<script>
function deletePopup(url) {
	var r = confirm("Do you want to delete the POI?");
	if (r == true) {
		window.open(url,"_self")
	}
}
</script>
<?php
	session_start();
	include("constants.php");
	$url = $apiURL."poi/".$_GET['poiId'];
	$username = $_GET['username'];
	$password = $_GET['password'];
	$login_data = array('username' => $username,
						'password' => $password);
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'GET',
			'content' => http_build_query($login_data),
		),
	);	
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	$json = json_decode($result,true);
	var_dump($result);
	if($result === "1"){
		
	}
?>