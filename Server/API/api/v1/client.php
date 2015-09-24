<?php
$url = 'http://localhost/ss2015/api/v1/api.php/acl/6';
$data = array('acl_password' => 'Koppe123',
              'username' => 'TestUser',
              'password' => 'TestPassword');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'PUT',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

?>
<html>
<head>
</head>
<body>
  URL: <?php echo $url; ?> </br>
  <pre><?php var_dump($result); ?></pre>
</body>
</html>
