<?php
$url = 'http://localhost/ss2015/api/v1/api.php/Poi';
/*$data = array('gps_long' => 12.34,
              'gps_lat' => 56.78,
              'username' => 'TestUser',
              'password' => 'TestPassword');*/
$data = array( 'gps_id' => 2,
                'location_id' => 3,
                  'poi_name' => "Test",
                  'poi_description' => "poi_description",
                  'poi_orientation' => "poi_orientation",
                  'poi_autoPlayMedia' => false,
                  'username' => "TestUser",
                  'password' => "TestPassword");
              /*
              $data = array('acl_password' => 'Koppe123',
                            'username' => 'TestUser',
                            'password' => 'TestPassword');*/
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo("GPS-adding one, new row:");
var_dump($result); ?>
