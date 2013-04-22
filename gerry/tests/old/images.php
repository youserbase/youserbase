<?php
	error_reporting(E_ALL);

	require './Images.class.php';
	array_map('unlink', glob('./foo.*.png'));

	$sizes = array(
		'small'=>array(300, 300),
		'avatar'=>array(-32, -32)
	);
	$filename = './5.large.png';

	$image = new Imagick($filename);
	$image->matteFloodfillImage('white',1, 'black', 0, 0);
	$image->writeImage('foo.png');

//	Images::Scale($filename, 'foo', './', $sizes);
?>
<style type="text/css">
body {
	background-color: #fe8;
}
img {
	padding: 3px;
	border: 1px solid black;
}
</style>
<?php
	foreach (glob('./*.png') as $file)
	{
		printf('<img src="%s" alt="" title="%s (%d)"/><br/>', $file, $file, filesize($file));
	}
?>