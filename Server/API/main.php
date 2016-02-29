<?php
session_start();
include("constants.php");
if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['location_id'])){
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$location_id = $_SESSION['location_id'];
} else  {
	$username = $_POST['username'];
	$password = $_POST['password'];
}

$login_data = array(
					'username' => $username,
					'password' => $password);
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
        'content' => http_build_query($login_data),
    ),
);
//var_dump($options);
$context  = stream_context_create($options);
//var_dump($context);
$result = file_get_contents($apiURL."poi", false, $context);
//var_dump($apiURL."poi");
//var_dump($result);
//var_dump(json_decode($result, true));
$json = json_decode($result,true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Organize your POIs</title>
		<link  href="login.css" rel= "stylesheet">
		<script>
			function deletePopup(url) {
				var r = confirm("Do you want to delete the POI?");
				if (r == true) {
					window.open(url,"_self")
				}
			}
		</script>
		<?php
			if($json['loggedIn'] === 1){
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
				$_SESSION['location_id'] = $json['location_id'];
			} else {
				session_destroy();
				echo "<meta http-equiv=\"refresh\" content=\"3;url=$baseURL\">";
			}
		?>

		</head>
		<body>
		<?php
			if (isset($_SESSION['username']) && isset($_SESSION['password'])){
		 ?>

		 <div id="content">
		 	<div class="table">
				<div class="row headerRow">
					<div class="col">
						Name
					</div>
					<div class="col">
						Description
					</div>
					<div class="col">
						Modify
					</div>
					<div class="col">
						Delete
					</div>
					<div class="col">
						Manage Media
					</div>
				</div>
				<?php
				$poiList = $json['Poi']['records'];
				foreach($poiList as &$poi) {
					?>
					<div class="row">
						<div class="col">
							<?php echo $poi[3]; ?>
						</div>
						<div class="col">
							<?php echo $poi[4]; ?>
						</div>
						<div class="col">
							<a href="poi.php?poiId=<?php echo $poi[0]; ?>"><img src="img_res/edit.png" alt="Modify" /></a>
						</div>
						<div class="col">
							<a href="#" onClick=<?php echo "\"deletePopup('./delete.php?poiId=$poi[0]')\"";?>><img src="img_res/Delete_24.png" alt="Delete" /></a>
						</div>
						<div class="col">
							<a href="medialist.php?poiId=<?php echo $poi[0];?>"><img src="img_res/Upload_24.png" alt="Media" /></a>
						</div>
					</div>
				<?php
					}
				?>
			 </div>

			 <a href="poi.php" class="button">
		 	 			Create new POI
			 </a>
		 </div>
<?php
	} else {
			?>
		<div>Login not successful, redirecting back to login in 3 seconds.</div>
		<?php
	}
  ?>
	</body>
</html>
