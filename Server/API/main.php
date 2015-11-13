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
$context  = stream_context_create($options);
$result = file_get_contents($apiURL."poi", false, $context);


//var_dump(json_decode($result, true));
$json = json_decode($result,true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Login Page : Brandberg</title>
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
		<table class="table table-bordered table-hover">
	                <thead>
	                    <tr>
	                    <th>POI#</th>
	                    <th>NAME</th>
						<th>DESCRIPTION</th>
						<th>MODIFY</th>
						<th>DELETE</th>
						<th>MEDIA</th>
	                    </tr>
	                </thead>
	                <tbody>
		<?php

		$poiList = $json['Poi']['records'];
		foreach($poiList as &$poi) {
			?>
				<tr>
		                    <td><?php echo $poi[0]; ?></td>
		                    <td><?php echo $poi[3]; ?></td>
		                    <td><?php echo $poi[4]; ?></td>
		                    <td class="text-center" ><a href="poi.php?poiId=<?php echo $poi[0]; ?>"><img src="img_res/edit.png" alt="Modify" /></a></td>
							<td class="text-center"><a href="#" onClick=<?php echo "\"deletePopup('./delete.php?poiId=$poi[0]')\"";?>><img src="img_res/Delete_24.png" alt="Delete" /></a></td>
							<td class="text-center"><a href="medialist.php?poiId=<?php echo $poi[0];?>"><img src="img_res/Upload_24.png" alt="Media" /></a></td>
		                    <!--td class="text-center"><a href="javascript: delete_user(<?php echo $row['id']; ?>)"><img src="images/delete_icon.png" alt="Delete" /></a></td -->
		         </tr>
		<?php
			}
      echo "</tbody>";
    	echo "</table>";
			echo '<a href="poi.php">Create new POI</a>';
		} else {
				?>
			<div>Login not successful, redirecting back to login in 3 seconds.</div>
			<?php
		}
  ?>
	</body>
</html>
