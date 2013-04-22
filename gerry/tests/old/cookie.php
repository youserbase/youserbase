<?php
	require '../../includes/bootstrap.inc.php';

	ob_start();

	echo Cookie::Get('language')."\n";
	Cookie::Set('language', 'de');
	echo Cookie::Get('language')."\n";
?>