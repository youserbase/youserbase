<?php
class Image
{
	private $filename;
	private $image, $stored_image;
	private $x0, $x1, $y0, $y1;
	private $background_color;

	public function __construct($filename)
	{
		$this->filename = $filename;
		$this->reset();
	}

	public function reset()
	{
		$this->image = new Imagick( $this->filename );
		$this->image->stripImage();

		$this->x0 = 0;
		$this->y0 = 0;
		$this->x1 = $this->image->getImageWidth() - 1;
		$this->y1 = $this->image->getImageHeight() - 1;
		$this->background_color = $this->image->getImagePixelColor(0, 0);

		return $this;
	}

	public function store()
	{
		$this->stored_image = $this->image->clone();

		return $this;
	}

	public function restore()
	{
		$this->image->destroy();
		$this->image = $this->stored_image->clone();

		return $this;
	}

	private function crop_along_side($run, $var, $inc, $threshold)
	{
		$remove = true;
		while ($remove and $this->x0 < $this->x1 and $this->y0 < $this->y1)
		{
			for ($i=$this->{$run.'0'}; $i<$this->{$run.'1'}; $i++)
			{
				$color = ($run=='x')
					? $this->image->getImagePixelColor($i, $this->$var)
					: $this->image->getImagePixelColor($this->$var, $i);
				$remove = ($remove and $color->isSimilar($this->background_color, $threshold));
			}
			if ($remove) {
				$this->$var += $inc;
			}
		}
	}

	public function crop($threshold)
	{
		$this->crop_along_side('x', 'y0', +1, $threshold); // Top > Bottom
		$this->crop_along_side('x', 'y1', -1, $threshold); // Bottom > Top
		$this->crop_along_side('y', 'x0', +1, $threshold); // Left > Right
		$this->crop_along_side('y', 'x1', -1, $threshold); // Right > Left

		$width = $this->x1 - $this->x0 + 1;
		$height = $this->y1 - $this->y0 + 1;

		$this->image->cropImage($width, $height, $this->x0, $this->y0);

		return $this;
	}

	public function scale($width, $height = null)
	{
		$destination_width	= $this->image->getImageWidth();
		$destination_height	= $this->image->getImageHeight();

		if ($height === null)
		{
			$height = $width;
		}

		if ($destination_width > $width)
		{
			$destination_height *= $width / $destination_width;
			$destination_width = $width;
		}
		if ($destination_height > $height)
		{
			$destination_width *= $height / $destination_height;
			$destination_height = $height;
		}

		$this->image->scaleImage($destination_width, $destination_height);

		return $this;
	}

	public function add_border($width, $color = null)
	{
		if ($color === null)
		{
			$color = $this->background_color;
		}
		$image = $this->image;
		$this->image = new Imagick();
		$this->image->newImage($image->getImageWidth() + 2 * $width, $image->getImageHeight() + 2 * $width, $color);
		$this->image->compositeImage($image, Imagick::COMPOSITE_COPY, $width, $width);

		$image->destroy();

		return $this;
	}

	public function size($width, $height)
	{
		$delta_width = $width - $this->image->getImageWidth();
		$delta_height = $height - $this->image->GetImageHeight();

		if ($delta_width > 0 or $delta_height > 0)
		{
			$image = $this->image;
			$this->image = new Imagick();
			$this->image->newImage($width, $height, $this->background_color);
			$this->image->compositeImage($image, Imagick::COMPOSITE_COPY, (int)$delta_width / 2, (int)$delta_height / 2);

			$image->destroy();
		}

		return $this;
	}

	public function save($filename, $extension = 'png')
	{
		$this->image->setCompressionQuality(1);
		$this->image->setImageFormat($extension);
		$this->image->writeImage($filename);

		chmod($filename, 0666);

		return $this;
	}

	public function __destruct()
	{
		$this->image->destroy();
		if (is_object($this->stored_image))
		{
			$this->stored_image->destroy();
		}
	}
}
?>