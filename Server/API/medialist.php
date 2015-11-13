<?php
session_start();
include("constants.php");
if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['location_id'])){
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$location_id = $_SESSION['location_id'];
}
$poiId = $_GET['poiId'];

$data = array();
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);

$result = file_get_contents($apiURL."Media?filter=poi_id,eq,$poiId", false, $context);

//var_dump(json_decode($result, true));
$json = json_decode($result,true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Media-Page</title>
		<link  href="login.css" rel= "stylesheet">
		<script>
			function deletePopup(url) {
				var r = confirm("Do you want to delete the Media?");
				if (r == true) {
					window.open(url,"_self")
				}
			}
		</script>
		</head>
		<body>
		<?php
			if (isset($_SESSION['username']) && isset($_SESSION['password'])){
		 ?>
		<table class="table table-bordered table-hover">
	                <thead>
                    <tr>
	                    <th>Name</th>
	                    <th>Content</th>
											<th>MediaType</th>
											<th>PageNumber</th>
											<th>MODIFY</th>
											<th>DELETE</th>
                    </tr>
	                </thead>
	                <tbody>
		<?php

		$mediaList = $json['Media']['records'];
		foreach($mediaList as &$media) {
			?>
				<tr>
		                    <td><?php echo $media[2]; ?></td>
		                    <td><?php echo $media[3]; ?></td>
		                    <td><?php echo $media[4]; ?></td>
												<td><?php echo $media[5]; ?></td>
		                    <td class="text-center" ><a href="media.php?poiId=<?php echo $poiId; ?>&mediaId=<?php echo $media[0]?>"><img src="./img_res/edit.png" alt="Modify" /></a></td>
							<td class="text-center"><a href="#" onClick=<?php echo "\"deletePopup('./deleteMedia.php?mediaId=$media[0]&poiId=$poiId')\"";?>><img src="./img_res/Delete_24.png" alt="Delete" /></a></td>
		         </tr>
		<?php
			}
      echo "</tbody>";
    	echo "</table>";
			echo "<a href='media.php?poiId=$poiId'>Create new media-entry</a>";
			?>
			<a href="main.php">Back to poi-list</a>
			<?php
		} else {
				?>
			<div>Login not successful, redirecting back to login in 3 seconds.</div>
			<?php
		}
  ?>
	</body>
</html>
