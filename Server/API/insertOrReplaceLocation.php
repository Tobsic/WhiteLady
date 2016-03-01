<?php
    session_start();
    include("constants.php");
    if (isset($_SESSION['username']) && isset($_SESSION['password'])){
    	$username = $_SESSION['username'];
    	$password = $_SESSION['password'];
    } else  {
    	$username = $_GET['username'];
    	$password = $_GET['password'];
    }

    if(isset($_SESSION['location_id'])){
      $location_id = $_SESSION['location_id'];
    } elseif(isset($_GET['location_id'])) {
      $location_id = $_GET['location_id'];
    }

    $location_name = $_POST['location_name'];
    $location_url = $_POST['location_url'];
    $location_banner_url = $_POST['location_banner_url'];
    $location_lat = $_POST['location_lat'];
    $location_long = $_POST['location_long'];
    $location_zoom = $_POST['location_zoom'];


    $locdata = array( 'location_name' => $location_name,
                      'location_url' => $location_url,
                      'location_banner_url' => $location_banner_url,
                      'location_lat' => $location_lat,
                      'location_long' => $location_long,
                      'location_zoom' => $location_zoom,
                      'username' => $username,
  								    'password' => $password);

  	// use key 'http' even if you send the request to https://...
    if(isset($location_id)){
      $locurl = $apiURL."Location/".$location_id;
      $locoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'PUT',
      			'content' => http_build_query($locdata),
      		),
    	);
    } else {
      $locurl = $apiURL."Gps";
    	$locoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'POST',
      			'content' => http_build_query($locdata),
      		),
    	);
    }

    $loccontext  = stream_context_create($locoptions);
  	$locresult = file_get_contents($locurl, false, $loccontext);
    if(!isset($location_id)){
      $location_id = (int) $locresult;
    }

    //exit(0);
    $redirect_url = "./main.php";
		header('Location: '.$redirect_url);
?>
