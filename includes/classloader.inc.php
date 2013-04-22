<?php
	$path = realpath(dirname(__FILE__).'/..');

	ClassLoader::AddDirectory($path.'/classes');
	ClassLoader::AddDirectory($path.'/gerry/controllers');

	spl_autoload_register(array('ClassLoader', 'Load'));
?>