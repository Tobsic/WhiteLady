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

    if(isset($location_id)){
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
      $result = file_get_contents($apiURL."Location/".$location_id, false, $context);

      $jsonLocation = json_decode($result,true);
      if(isset($jsonLocation) && isset($jsonLocation["location_id"])){
          $location = array (
              "location_id" => $location_id,
              "location_name" => $jsonLocation["location_name"],
              "location_url" => $jsonLocation["location_url"],
              "location_banner_url" => $jsonLocation["location_banner_url"],
              "location_lat" => $jsonLocation["location_lat"],
              "location_long" => $jsonLocation["location_long"],
              "location_zoom" => $jsonLocation["location_zoom"]
          );
      }
    }

 ?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
 		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?php
        if(isset($location)){
          echo "<title>Modifying ".$location["location_name"]."</title>";
        } else {
          echo "<title>Creating a new location</title>";
        }
      ?>
 		<link  href="login.css" rel= "stylesheet">

  </head>
  <body>
    <div id="content">
      <form enctype="multipart/form-data" action="insertOrReplaceLocation.php" method="post">
        <?php if(isset($location_id))echo "<input type='hidden' name='location_id' value='$location_id'>"; ?>
        <div class="form_divider"><h3>General information:</h3></div>
        <div class="form_row">
          <label for="location_name">Location-Name*:</label>
          <input type="text" id="location_name" name="location_name" maxlength="255" required="1" <?php if(isset($location)){echo "value=\"".$location["location_name"]."\"";}?>>
        </div>
        <div class="form_row">
          <label for="location_url">Location-URL*:</label>
          <input type="text" id="location_url" name="location_url" maxlength="255" required="1" <?php if(isset($location)){echo "value=\"".$location["location_url"]."\"";}?>>
        </div>
        <div class="form_row">
          <label for="location_banner_url">Location-Banner-URL*:</label>
          <input type="text" id="location_banner_url" name="location_banner_url" maxlength="255" required="1" <?php if(isset($location)){echo "value=\"".$location["location_banner_url"]."\"";}?>>
        </div>
        <div class="form_divider"><h3>Positioning information:</h3></div>
        <div class="form_row">
            <label for="location_lat">Latitude*:</label>
            <input type="number" id="location_lat" name="location_lat"  required="1" maxlength="22" min="-99" max="99" step="0.000000000000000001" <?php if(isset($location)) echo "value=\"".$location["location_lat"]."\"";?>></br>
        </div>
        <div class="form_row">
            <label for="location_long">Longitude*:</label>
            <input type="number" id="location_long" name="location_long" required="1" maxlength="25" min="-99" max="99" step="0.00000000000000001" <?php if(isset($location)) echo "value=\"".$location["location_long"]."\"";?>></br>
        </div>
        <div class="form_row">
            <label for="location_zoom">Map-Zoom*:</label>
            <input type="number" id="location_zoom" name="location_zoom" required="1" min="0" max="99" step="1" <?php if(isset($location)) echo "value=\"".$location["location_zoom"]."\"";?>></br>
        </div>
        <div class="form_row">
          <input type="submit" />
          <input type="reset" />
       </div>
      </form>
      <a href="main.php" class="button">Back</a>
    </div>
  </body>
</html>
