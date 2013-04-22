<?php
/**
 * @todo HTML-Kommentare entfernen ausser Conditional Comments
 */
function filter_stripwhitespace($content)
{
//	$content = preg_replace('/<!--(?!\[if).*?-->/isxm', '', $content);

	$MARKER = '@@@TRIM_FILTER_NOREPLACE@'.md5(uniqid('filter', true)).'@@@';

	preg_match_all('/<(script|pre|textarea)[^>]*?>.*?<\/\\1>/is', $content, $stored_contents);
	$content = str_replace($stored_contents[0], $MARKER, $content);

	$content = preg_replace('/(?:^\s+|\s+$)/m', '', $content);

	$parts = explode($MARKER, $content);
	$result = array_shift($parts);
	foreach ($parts as $part)
	{
		$result .= array_shift($stored_contents[0]).$part;
	}

	return $result;
}
?>