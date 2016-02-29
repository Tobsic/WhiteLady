<?php
include_once("password.php");
$pw = password_hash("TestPassword", PASSWORD_DEFAULT);
echo $pw;
?>