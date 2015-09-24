<?php
$url = 'http://localhost/ss2015/api/v1/api.php/Poi';
$data = array('location_id' => 3,
              'media_id' => 1,
              'gps_id' => 2,
              'poi_type' => "Text",
              'poi_name' => "TestPoi2",
              'poi_description' => "Description",
              'username' => 'Spitz',
              'password' => 'Koppe123');
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

echo($result); ?>
