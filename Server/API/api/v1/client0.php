<?php
$url = 'http://localhost/ss2015/api/v1/api.php/Acl';
$data = array('username' => 'Spitz',
              'password' => 'Koppe123');
              /*
              $data = array('acl_password' => 'Koppe123',
                            'username' => 'TestUser',
                            'password' => 'TestPassword');*/
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$json =json_decode($result, true);
echo("ACL - NoAccess expected:");
echo($result); ?>
