<?php
class Youser_Image
{
	private static $sizes = array(
		'avatar'=>array(-48, -48),
		'small'=>array(80, 80),
		'medium'=>array(120, 120),
		'large'=>array(200, 300),
		'original'=>array(0,0)
	);

	public static function GetLink($youser_id, $size = 'original')
	{
		$filename = 'Youser/'.$size.'/'.$youser_id.'.png';

		$index = hexdec(substr(md5($youser_id.$size), -4)) % count($GLOBALS['ASSETS_URLS']);
		$url = $GLOBALS['ASSETS_URLS'][$index];

		return $url.$filename;
	}

	public static function Upload($youser_id, $FILE_SETTINGS)
	{
		$path = ASSETS_IMAGE_DIR.'yousers'.'/'.ltrim(substr($youser_id, max(-3, -strlen($youser_id))), '0');
		if (!file_exists($path))
		{
			if (!mkdir($path))
				return false;
			chmod($path, 0777);
		}

		return Images::Scale($FILE_SETTINGS['tmp_name'], $youser_id, $path, self::$sizes);
	}

	public static function Remove($youser_id)
	{
		$path = ASSETS_IMAGE_DIR.'yousers/'.ltrim(substr($youser_id, max(-3, -strlen($youser_id))), '0').'/';
		array_map('unlink', glob($path.$youser_id.'.*'));
		if (!count(glob($path.'/*.*')))
			rmdir($path);
	}
}
?>