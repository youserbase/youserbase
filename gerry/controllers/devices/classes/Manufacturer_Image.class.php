<?php
class Manufacturer_Image
{
	public static function Process($name, $filename)
	{
		$new_filename = dirname($filename).'/'.strtolower( preg_replace('/[^[:alnum:]]/', '', $name) ).'_logo';

		$logo = new Image($filename);
		$logo->crop(0.1);
		$logo->store();

		// Delete old files
		array_map('unlink', glob($new_filename.'.{tiny,small}.png', GLOB_BRACE));

		// Tiny, scaled to 100x32
		$logo->scale(100, 32);
		$logo->save($new_filename.'.tiny.png');

		// Small, scaled to 70x70 width 15px border (= 100x100)
		$logo->restore();

		$logo->scale(70, 70);
		$logo->size(70, 70);
		$logo->add_border(15);
		$logo->save($new_filename.'.small.png');
	}
}
?>