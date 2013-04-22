<?php
class Images
{
	const extension = 'png';
	const imagefunction = 'imagepng';
	const imagefunctionparameter = '9';

	public static function Scale($filename, $base_filename, $destination_directory, $sizes)
	{
		if (!file_exists($filename) or !is_readable($filename))
		{
			throw new Exception('Unable to read filename "'.$filename.'"');
		}

		$source = imagecreatefromstring(file_get_contents($filename));
		
		if (!is_resource($source))
		{
			throw new Exception('Invalid image format');
		}

		$source_width = imagesx($source);
		$source_height = imagesy($source);

		$source_x0 = 0;
		$source_y0 = 0;

		foreach ($sizes as $type=>$size)
		{
			$destination_filename = sprintf('%s/%s.%s.%s',
				is_dir($destination_directory) ? $destination_directory : dirname($destination_directory),
				$base_filename,
				$type,
				self::extension
			);

			$destination_width = $source_width;
			$destination_height = $source_height;
			$destination_x0 = 0;
			$destination_y0 = 0;

			if ($size[0]!=0 and $destination_width>abs($size[0]))
			{
				$destination_height *= abs($size[0])/$destination_width;
				$destination_width = abs($size[0]);
			}
			if ($size[1]!=0 and $destination_height>abs($size[1]))
			{
				$destination_width *= abs($size[1])/$destination_height;
				$destination_height = abs($size[1]);
			}

			$image_width = $destination_width;
			$image_height = $destination_height;

			if ($size[0]<0)
			{
				$destination_x0 = floor((abs($size[0])-$destination_width)/2);
				$image_width = abs($size[0]);
			}
			if ($size[1]<0)
			{
				$destination_y0 = floor((abs($size[1])-$destination_height)/2);
				$image_height = abs($size[1]);
			}

			$destination = imagecreatetruecolor($image_width, $image_height);
			imagealphablending($destination, false);
			$transparent_color = imagecolorallocatealpha($destination, 0, 0, 0, 127);
			imagefill($destination, 0, 0, $transparent_color);
			imagesavealpha($destination, true);

			imagecopyresampled($destination, $source,
				$destination_x0, $destination_y0,
				$source_x0, $source_y0,
				$destination_width, $destination_height,
				$source_width, $source_height
			);

			call_user_func(self::imagefunction, $destination, $destination_filename, self::imagefunctionparameter);
			chmod($destination_filename, 0777);

			imagedestroy($destination);
		}
		imagedestroy($source);

		return true;
	}
}
?>