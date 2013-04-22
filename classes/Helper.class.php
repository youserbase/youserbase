<?php
class Helper
{
	private static $nonce = null;

	public static function registerClasses($directory)
	{
		if (is_array($directory))
			array_map(array('self', 'registerClasses'), $directories);
		else
		{
			array_map(array('self', 'registerClasses'), glob($directory.'/*', GLOB_ONLYDIR | GLOB_NOSORT));
			foreach (glob($directory.'/*.'.sql_regcase('class.php'), GLOB_NOSORT) as $file)
				include_once $file;
		}
	}

	public static function GetIconForRole($role)
	{
		$assignments = array(
			'god'=>'eye',
			'root'=>'user_suit',
			'administrator'=>'user_gray',
			'moderator'=>'user_red',
			'dealer'=>'user_orange',
			'youser'=>'user_green',
			'none'=>'user'
		);
		return Assets::Image('famfamfam/'.$assignments[$role].'.png');
	}

	public static function GetUploadedFiles($index=null)
	{
		if ($index!==null and !isset($_FILES[$index]))
			return false;

		$result = array();
		foreach ($_FILES as $outer_index=>$data)
		{
			$files = array();
			foreach ($data['name'] as $inner_index=>$name)
				$files[$inner_index] = array(
					'name'=>$name,
					'type'=>$data['type'][$inner_index],
					'tmp_name'=>$data['tmp_name'][$inner_index],
					'error'=>$data['error'][$inner_index],
					'size'=>$data['size'][$inner_index]
				);
			$result[$outer_index] = $files;
		}

		return $index !== null
			? $result[$index]
			: $result;
	}

	public static function GetNonce()
	{
		if (self::$nonce === null)
			self::$nonce = md5(uniqid('nonce used for one impression', true));
		return self::$nonce;
	}

	public static function ExtractOptions($option_strings)
	{
		$options = array();
		foreach ($option_strings as $string)
		{
			$data = explode(':', $string);
			if (isset($data[3]))
				$data[3] = explode(',', $data[3]);

			if (!isset($options[$data[0]]))
				$options[$data[0]] = array();
			$options[$data[0]][$data[1]] = array_slice($data, 2);
		}

		ksort($options);
		array_map('ksort', &$options);

		return $options;
	}

	public static function GetCountries()
	{
		static $countries = null;
		if ($countries === null)
			$countries = DBManager::Get()->query("SELECT iso, printable_name FROM country")->to_array('iso', 'printable_name');
		return $countries;
	}

}
?>