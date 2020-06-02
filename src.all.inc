<?php 
   $getconfigs = parse_ini_file('config.ini');
   define('DB_USER',$getconfigs['DB_USER']);
   define('DB_HOST',$getconfigs['DB_HOST']);
   define('DB_NAME',$getconfigs['DB_NAME']);
   define('DB_PASS',$getconfigs['DB_PASSWORD']);
?>