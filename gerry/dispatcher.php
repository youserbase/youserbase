<?php
	define('DISPATCHED', true);

	require '../includes/bootstrap.inc.php';
	Timer::Report('Dispatching...');

	$uri = ltrim(str_replace(dirname($_SERVER['PHP_SELF']).'/', '/', $_SERVER['REQUEST_URI']), '/');

	Dispatcher::Resolve($uri);

	require 'index.php';
?>