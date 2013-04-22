<?php 	
	require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
	require dirname(__FILE__).'/../classes/ClassLoader.class.php';
	require dirname(__FILE__).'/../includes/classloader.inc.php';
	require dirname(__FILE__).'/../includes/config.inc.php';
	require dirname(__FILE__).'/../includes/functions.inc.php';
	
	DBManager::Get('devices')->query("UPDATE mail_limiter SET mail_limit = 25 WHERE mail_limit != 25;");
?>