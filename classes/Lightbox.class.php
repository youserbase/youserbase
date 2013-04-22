<?php
/**
 * Lightbox is just a simple adapter to communicate to the javascript lightbox
 * on the page via AJAX.
 *
 * @author Jan-Hendrik Willms <tleilax@mindfuck.de>
 * @version 1.0
 * @static
 */
class Lightbox
{
	/**
	 * Close the lightbox (if open)
	 */
	public static function Close()
	{
		if ($GLOBALS['VIA_AJAX'])
		{
			Header('X-Close-Lightbox: true');
		}
	}
}
?>