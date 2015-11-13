<?php
    session_start();
    include("constants.php");
    if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['location_id'])){
    	$username = $_SESSION['username'];
    	$password = $_SESSION['password'];
    	$location_id = $_SESSION['location_id'];
    } else  {
    	$username = $_GET['username'];
    	$password = $_GET['password'];
    	$location_id = $_GET['location_id'];
    }
    if(isset($_POST['poi_id'])){
       $poi_id = (int)$_POST['poi_id'];
    }
    if(isset($_POST['gps_id'])){
       $gps_id = (int)$_POST['gps_id'];
    }

    $poi_name = $_POST['poi_name'];
    $poi_description = $_POST['poi_description'];
    $poi_orientation = $_POST['poi_orientation'];
    $gps_longitude = $_POST['gps_longitude'];
    $gps_latitude = $_POST['gps_latitude'];
    $poi_autoPlayMedia = isset($_POST['poi_autoPlayMedia']);


    $gpsdata = array( 'gps_long' => $gps_longitude,
                      'gps_lat' => $gps_latitude,
                      'username' => $username,
  								    'password' => $password);

  	// use key 'http' even if you send the request to https://...
    if(isset($gps_id)){
      $gpsurl = $apiURL."Gps/".$gps_id;
      $gpsoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'PUT',
      			'content' => http_build_query($gpsdata),
      		),
    	);
    } else {
      $gpsurl = $apiURL."Gps";
    	$gpsoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'POST',
      			'content' => http_build_query($gpsdata),
      		),
    	);
    }
    $gpscontext  = stream_context_create($gpsoptions);
  	$gpsresult = file_get_contents($gpsurl, false, $gpscontext);
    if(!isset($gps_id)){
      $gps_id = (int) $gpsresult;
    }

    if(isset($gps_id) && $gps_id >= 0){
    $poidata = array( 'gps_id' => $gps_id,
                      'location_id' => $location_id,
                      'poi_name' => $poi_name,
                      'poi_description' => $poi_description,
                      'poi_orientation' => $poi_orientation,
                      'poi_autoPlayMedia' => $poi_autoPlayMedia,
                      'username' => $username,
                      'password' => $password);

    // use key 'http' even if you send the request to https://...
    if(isset($poi_id)){

      $poiurl = $apiURL."Poi/".$poi_id;
      $poioptions = array(
          'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'PUT',
            'content' => http_build_query($poidata),
          ),
      );
    } else {
      $poiurl = $apiURL."Poi";
      $poioptions = array(
          'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($poidata),
          ),
      );
    }

    $poicontext  = stream_context_create($poioptions);
    $poiresult = file_get_contents($poiurl, false, $poicontext);

    }
    //exit(0);
    $redirect_url = "./main.php";
		header('Location: '.$redirect_url);
?>
