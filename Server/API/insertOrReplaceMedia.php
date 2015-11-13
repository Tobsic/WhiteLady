<?php
    session_start();
    include("constants.php");
    if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['location_id'])){
    	$username = $_SESSION['username'];
    	$password = $_SESSION['password'];
    	$location_id = $_SESSION['location_id'];
    }

    if(isset($_POST['poi_id'])){
       $poi_id = (int)$_POST['poi_id'];
    }

    if(isset($_POST['media_id'])){
       $media_id = (int)$_POST['media_id'];
    }

    $media_content = $_POST['media_content'];
    if(isset($_POST['media_type'])){
      $media_type = $_POST['media_type'];
    }

    $media_name = $_POST['media_name'];
    $media_pagenumber = (int)$_POST['media_pagenumber'];

    if(isset($media_content) && !($media_content === "")){
      if(substr($media_content,0,4) === "http"){
        $headers = get_headers($media_content);
        $media_type = substr($headers[8],14);
      } else {
        $media_type = "text/plain";
      }
    }
    if (isset($_FILES['media_file']) && strlen($_FILES['media_file']['name']) > 0){
      $file = $_FILES['media_file'];
      $t = explode(".", $file['name']);
      $t = array_reverse($t);

      $filename = md5($t[1].time()).".".$t[0];
      $uploadfile = "upload/".$filename;
      $uploadfileURL = $baseURL."/".$uploadfile;

      $upload = move_uploaded_file($file['tmp_name'], $uploadfile);
      if($upload){
        $media_type = $file['type'];
        $media_content = $uploadfileURL;
      } else {
        echo "Error with uploading the file!";
        exit();
      }
    }

    $mediadata = array('poi_id' => $poi_id,
                      'media_name' => $media_name,
                      'media_content' => $media_content,
                      'media_type' => $media_type,
                      'media_pagenumber' => $media_pagenumber,
                      'username' => $username,
  								    'password' => $password);

    // use key 'http' even if you send the request to https://...
    if(isset($media_id)){
      $mediaurl = $apiURL."Media/".$media_id;
      $mediaoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'PUT',
      			'content' => http_build_query($mediadata),
      		),
    	);
    } else {
      $mediaurl = $apiURL."Media";
    	$mediaoptions = array(
      		'http' => array(
      			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      			'method'  => 'POST',
      			'content' => http_build_query($mediadata),
      		),
    	);
    }
    $mediacontext  = stream_context_create($mediaoptions);
  	$mediaresult = file_get_contents($mediaurl, false, $mediacontext);
    if(!isset($media_id)){
      $media_id = (int) $mediaresult;
    }

    $redirect_url = "./medialist.php?poiId=".$poi_id;
		header('Location: '.$redirect_url);
?>
