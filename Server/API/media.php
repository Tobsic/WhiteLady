<?php
    session_start();
    include("constants.php");
    if(isset($_GET['poiId'])){
  	   $poi_id = $_GET['poiId'];
    }
    if(isset($_GET['mediaId'])){
  	   $media_id = $_GET['mediaId'];
    }
    if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['location_id'])){
    	$username = $_SESSION['username'];
    	$password = $_SESSION['password'];
    	$location_id = $_SESSION['location_id'];
    } else  {
    	$username = $_POST['username'];
    	$password = $_POST['password'];
    	$location_id = $_POST['location_id'];
    }
    if(isset($media_id)){
      $login_data = array();
      // use key 'http' even if you send the request to https://...
      $options = array(
          'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'GET',
              'content' => http_build_query($login_data),
          ),
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($apiURL."Media/".$media_id, false, $context);

      $jsonMedia = json_decode($result,true);
      if(isset($jsonMedia) && isset($jsonMedia["media_id"])){
          $media = array (
              "media_id" => $media_id,
              "poi_id" => $poi_id,
              "media_name" => $jsonMedia["media_name"],
              "media_content" => $jsonMedia["media_content"],
              "media_type" => $jsonMedia["media_type"],
              "media_pagenumber" => $jsonMedia["media_pagenumber"],
          );
      }
    }

 ?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
 		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?php
        if(isset($media)){
          echo "<title>Modifying ".$media["media_name"]."</title>";
        } else {
          echo "<title>Creating a new Media-Page</title>";
        }
      ?>
 		<link  href="login.css" rel= "stylesheet">

  </head>
  <body>
    <form enctype="multipart/form-data" action="insertOrReplaceMedia.php" method="post">
      <?php if(isset($poi_id))echo "<input type='hidden' name='poi_id' value='$poi_id'>"; ?>
      <?php if(isset($media_id))echo "<input type='hidden' name='media_id' value='$media_id'>"; ?>
      <div class="form_divider"><h3>General information:</h3></div>
      <div class="form_row">
        <label for="media_name">Media-Name*:</label>
        <input type="text" id="media_name" name="media_name" maxlength="255" required="1" <?php if(isset($media)){echo "value=\"".$media["media_name"]."\"";}?>>
      </div>
      <div class="form_row">
        <label for="media_pagenumber">PageNumber*:</label>
        <input type="number" id="media_pagenumber" name="media_pagenumber" maxlength="99" min="0" required="1"<?php if(isset($media)) echo "value=\"".$media["media_pagenumber"]."\"";?>>
      </div>
      <div class="form_divider"><h3>Media:</h3></div>
      <div class="form_row">
        <label for="media_file">Upload new file:</label>
        <input type="file" id="media_file" name="media_file">
      </div>
      <div class="form_row">
        <label for="media_content">Media-URL / Content:</label>
        <input type="text" id="media_content" name="media_content" maxlength="10000" <?php if(isset($media)){echo "value=\"".$media["media_content"]."\"";}?>>
        <?php if(isset($media)) {
              echo "<span>Media-Type: <i>".$media["media_type"]."</i></span>";
              echo "<input type=\"hidden\" name=\"media_type\" value=\"".$media["media_type"]."\">";
            } ?>
      </div>
      <div class="form_row">
        <input type="submit" />
        <input type="reset" />
     </div>
    </form>
    <a href="medialist.php?poiId=<?php echo $poi_id; ?>">Back to media-list</a>
  </body>
</html>
