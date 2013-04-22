<?php
class savePicture
{
	private static $sizes = array(
		'small'=>array(80, 80),
		'medium'=>array(120, 120),
		'large'=>array(300, 300)
	);
	
	public static function save($device_id)
	{
		$picture_type = $_POST['picture_type'];
		foreach ($_FILES['picture_source_path']['name'] as $key => $file_info)
		{
			$path = ASSETS_DIR.'device_images';
			$youser_id = Youser::Id();
			$device_picture = new device_pictures();
			$device_picture->device_pictures_id = md5(uniqid($file_info.time(true)));
			$device_picture->device_id = $device_id;
			$device_picture->youser_id = $youser_id;
			$device_picture->device_pictures_type = $picture_type;
			if (!file_exists($path) and !mkdir($path))
			{
				return false;
			}
			$path .= '/'.substr($device_id, 0, 4);
			if (!file_exists($path) and !mkdir($path))
			{
				return false;
			}
			$filename = $path.'/'.$device_picture->device_pictures_id;
			$file_parts = explode('.', $file_info);
			$original_file_extension = str_replace('jpg', 'jpeg', strtolower(array_pop($file_parts)));
			if (!move_uploaded_file($_FILES['picture_source_path']['tmp_name'][$key], $filename.$device_picture->device_pictures_type.'.original.'.$original_file_extension))
			{
				return false;
			}
			chmod($filename.$device_picture->device_pictures_type.'.original.'.$original_file_extension, 0777);
	
			$device_picture->device_pictures_path = $filename.$device_picture->device_pictures_type.'.small.'.$original_file_extension;
			$device_picture->save();
			self::ScaleImage($filename.$device_picture->device_pictures_type.'.original.'.$original_file_extension);
		}
		return true;
	}
	
	private static function ScaleImage($filename)
	{
		$source = imagecreatefromstring(file_get_contents($filename));
		foreach (self::$sizes as $index=>$size)
		{
			$width = imagesx($source);
			$height = imagesy($source);
			if ($width>$size[0])
			{
				$height *= $size[0]/$width;
				$width = $size[0];
			}
			if ($height>$size[1])
			{
				$width *= $size[1]/$height;
				$height = $size[1];
			}

			$destination = imagecreatetruecolor(floor($width), floor($height));

			imagealphablending($destination, false);
            $colorTransparent = imagecolorallocatealpha($destination, 0, 0, 0, 127);
            imagefill($destination, 0, 0, $colorTransparent);
            imagesavealpha($destination, true);

            imagecopyresampled($destination, $source, 0, 0, 0, 0, floor($width), floor($height), imagesx($source), imagesy($source));

			$file_parts = explode('.', basename($filename));
			imagepng($destination, dirname($filename).'/'.array_shift($file_parts).'.'.$index.'.png');

			imagedestroy($destination);
		}
		imagedestroy($source);

		return true;
	}
}
?>