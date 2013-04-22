<?php
error_reporting(0);
Header('Content-Type: text/plain');

foreach (glob('../assets/manufacturer_images/*.*') as $file)
{
	if (!chmod($file, 0666))
	{
		echo $file."\n";
	}
}

?>