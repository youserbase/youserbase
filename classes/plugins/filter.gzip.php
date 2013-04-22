<?php
function filter_gzip($content)
{
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')!==false)
	{
		Header('Content-Encoding: gzip');
		$content = gzencode($content, 9);
	}
	elseif (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate')!==false)
	{
		Header('Content-Encoding: deflate');
		$content = gzdeflate($content, 9);
	}

	Header('Content-Length: '.strlen($content));

	return $content;
}
?>