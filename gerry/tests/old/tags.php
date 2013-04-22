<?php
	Header('Content-Type: text/plain');
	error_reporting(E_ALL);

	require '../../includes/bootstrap.inc.php';

	$foo = 'this is <tag keks id="herr" a="b" c="d">foo</tag>. you cannot trust <tag id="willms" e="f" keks/> the <tag/> tag.';

	$result = TagParser::Parse($foo, 'tag', 'id');
	var_dump($result);
?>