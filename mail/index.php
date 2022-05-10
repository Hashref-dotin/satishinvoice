<?php
$to="raj@cybrain.co.in";
$fn="Fisrt Name";
$ln="Last Name";
$name=$fn.' '.$ln;
$from="support@hitbullseye.com";
$subject = "Welcome to Website";
$message = "Dear $name, 


Your Welcome Message.


Thanks
www.website.com
";
include('smtpwork.php');

?>