<?php
    session_start();
    include("constants.php");
    if(isset($_GET['poiId'])){
  	   $poi_id = $_GET['poiId'];
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
    if(isset($poi_id)){
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
      $result = file_get_contents($apiURL."Poi/".$poi_id, false, $context);

      $jsonPoi = json_decode($result,true);
      if(isset($jsonPoi) && isset($jsonPoi["poi_id"])){
        $resultGps = file_get_contents($apiURL."Gps/".$jsonPoi["gps_id"], false, $context);
        $jsonGps = json_decode($resultGps,true);
        if(isset($jsonGps) && isset($jsonGps["gps_id"])){
          $poi = array (
              "poi_id" => $poi_id,
              "gps_id" => $jsonGps["gps_id"],
              "location_id" => $location_id,
              "poi_name" => $jsonPoi["poi_name"],
              "poi_description" => $jsonPoi["poi_description"],
              "poi_orientation" => $jsonPoi["poi_orientation"],
              "poi_autoPlayMedia" => $jsonPoi["poi_autoPlayMedia"],
              "gps_longitude" => $jsonGps["gps_long"],
              "gps_latitude" => $jsonGps["gps_lat"],
          );
        }
      }
    }
 ?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 	<head>
 		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <?php
        if(isset($poi)){
          echo "<title>Modifying ".$poi["poi_name"]."</title>";
        } else {
          echo "<title>Creating a new PointOfInterest</title>";
        }
      ?>
 		<link  href="login.css" rel= "stylesheet">

  </head>
  <body>
    <div id="content">
    <form action="insertOrReplacePoi.php" method="post">
      <?php if(isset($poi_id))echo "<input type='hidden' name='poi_id' value='$poi_id'>"; ?>
      <?php if(isset($poi))echo "<input type='hidden' name='gps_id' value='".$poi["gps_id"]."'>"; ?>
      <?php echo "<input type='hidden' name='location_id' value='".$location_id."'>"; ?>
      <div class="form_divider"><h3>General information:</h3></div>
      <div class="form_row">
        <label for="poi_name">POI-Name*:</label>
        <input type="text" id="poi_name" name="poi_name" maxlength="255" required="1" <?php if(isset($poi)){echo "value=\"".$poi["poi_name"]."\"";}?>>
      </div>
      <div class="form_row">
        <label for="poi_description">POI-Description*:</label>
        <input type="text" id="poi_description" name="poi_description" maxlength="1000" required="1"<?php if(isset($poi)) echo "value=\"".$poi["poi_description"]."\"";?>>
      </div>
      <div class="form_divider"><h3>Positioning:</h3></div>
      <div class="form_row">
          <label for="poi_orientation">Orientation (in degrees):</label>
          <input type="number" id="poi_orientation" name="poi_orientation"  maxlength="5" min="0" max="360" step="0.01" <?php if(isset($poi)) echo "value=\"".$poi["poi_orientation"]."\"";?>>
      </div>
      <div class="form_row">
          <label for="gps_longitude">Longitude*:</label>
          <input type="number" id="gps_longitude" name="gps_longitude" required="1" maxlength="25" min="-99" max="99" step="0.00000000000000001" <?php if(isset($poi)) echo "value=\"".$poi["gps_longitude"]."\"";?>></br>
      </div>
      <div class="form_row">
          <label for="gps_latitude">Latitude*:</label>
          <input type="number" id="gps_latitude" name="gps_latitude"  required="1" maxlength="22" min="-99" max="99" step="0.000000000000000001" <?php if(isset($poi)) echo "value=\"".$poi["gps_latitude"]."\"";?>></br>
      </div>
      <div class="form_row">
          <label for="poi_autoPlayMedia">Automatical show POI:</label>
          <input type="checkbox" id="poi_autoPlayMedia" name="poi_autoPlayMedia[]" value="true"  <?php if(isset($poi))if($poi["poi_autoPlayMedia"] === "1")echo "checked=\"checked\"";?>>
        </div>
      <div class="form_row">
        <input type="submit" />
        <input type="reset" />
     </div>
    </form>
    <a href="main.php" class="button">Back to poi-list</a>
    </div>
  </body>
</html>
