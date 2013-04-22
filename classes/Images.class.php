<?php
class Images
{
	const extension = 'png';

	public static function Scale($filename, $base_filename, $destination_directory, $sizes)
	{
		try
		{
			$source = new Imagick($filename);
			$source->stripImage();
		}
		catch (ImagickException $e)
		{
			throw new Exception('Unable to read filename "'.$filename.'" ('.$e->getMessage().')');
		}

		$source_width = $source->getImageWidth();
		$source_height = $source->getImageHeight();

		$source_x0 = 0;
		$source_y0 = 0;

		foreach ($sizes as $type=>$size)
		{
			$destination = $source->clone();

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

			if ($destination_width<$source_width and $destination_height<$source_height)
			{
				$destination->thumbnailImage($destination_width, $destination_height, true);
				$destination->setImageType(Imagick::IMGTYPE_PALETTEMATTE);
			}
			else
			{
				$destination->sampleImage($destination_width, $destination_height);
			}

			if ($destination_width<$image_width or $destination_height<$image_height)
			{
				$canvas = $destination;
				$destination = new Imagick();
				$destination->newImage($image_width, $image_height, new ImagickPixel('transparent'));

				$destination->compositeImage($canvas, Imagick::COMPOSITE_DEFAULT, $destination_x0, $destination_y0);

				$canvas->destroy();
				unset($canvas);
			}

			$destination->setCompressionQuality(1);
			$destination->setImageFormat(self::extension);
			$destination->writeImage($destination_filename);

			@chmod($destination_filename, 0777);

			$destination->destroy();
			unset($destination);
		}
		$source->destroy();
		unset($source);

		return true;
	}
}
?>