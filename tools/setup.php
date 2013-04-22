<?php
	$path = ASSETS_IMAGE_DIR.'yousers';
	if (!file_exists($path) and !mkdir($path))
	{
		return false;
	}
?>