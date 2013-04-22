<?php
function filter_utf8($content)
{
	Header('Content-type: text/html; charset=utf-8');
	return mb_convert_encoding($content, 'utf-8');
}
?>