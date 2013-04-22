<?php
	require '../classes/Assets.class.php';
	require '../classes/Cache.class.php';

	$supported_types = array(
		'css'=>array(
			'Content-Type'=>'text/css',
			'gzip'=>true,
		),
		'js'=>array(
			'Content-Type'=>'text/javascript',
			'gzip'=>true,
		),
		'gif'=>array(
			'Content-Type'=>'image/gif',
		),
		'jpeg'=>array(
			'Content-Type'=>'image/jpeg',
		),
		'jpg'=>array(
			'Content-Type'=>'image/jpeg',
		),
		'png'=>array(
			'Content-Type'=>'image/png',
		),
	);

	if (empty($_GET['type']) or !isset($supported_types[$_GET['type']]))
	{
		header($_SERVER['SERVER_PROTOCOL'].' 503 Not Implemented', null, 503);
		header('Status: 503 Not Implemented', null, 503);
		die;
	}

	$filename = (strpos($_REQUEST['file'], 'cache/')===0)
		? Cache::GetDirectory($_REQUEST['type']).'/'.substr($_REQUEST['file'], 6)
		: $_REQUEST['file'];

	if (!file_exists($filename))
	{
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not found', null, 404);
		header('Status: 404 Not found', null, 404);
		die;
	}

	$last_modified = max(filemtime(__FILE__), filemtime($filename));
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and $last_modified<=strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']))
	{
		header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified', null, 304);
		header('Status: 304 Not Modified', null, 304);
		die;
	}

	$content = file_get_contents($filename);
	$type = $supported_types[ $_GET['type'] ];

	if (!empty($type['gzip']))
	{
		$content = Assets::Zip($content);
	}

	header('Content-Type: '.$type['Content-Type']);
	header('Content-Length: '.strlen($content));
	header('Cache-Control: public, max-age=315360000');
	header('Vary: Accept-Encoding');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') .' GMT');
	header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('+10 years')).' GMT');

	print $content;
?>