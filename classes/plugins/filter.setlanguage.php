<?php
function filter_setlanguage($content, $language)
{
	Header('Content-Language: '.$language);

	return $content;
}
?>