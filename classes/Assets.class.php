<?php
class Assets
{
	private static $minify = false;
	private static $combine = false;
	private static $wrapper = '';
	private static $files = array();
	private static $excludes = array();
	private static $types = array(
		'css'=>array(
			'filter' => array('Assets_CSS', 'filter'),
			'template' => "<link href=\"%s\" rel=\"stylesheet\" type=\"text/css\"/>\n",
		),
		'js' => array(
			'filter' => array('Assets_JS', 'filter'),
			'template' => "<script src=\"%s\" type=\"text/javascript\"></script>\n",
		),
	);

	public static function Add($file)
	{
		$files = func_get_args();
		self::$files = array_merge(self::$files, $files);
	}

	public static function Glob($path)
	{
		self::$files = array_merge(self::$files, glob($path));
	}

	public static function Without($file)
	{
		$files = func_get_args();
		self::$excludes = array_merge(self::$excludes, $files);
	}

	public static function Wrap($wrapper)
	{
		self::$wrapper = $wrapper;
	}

	private static function Files($type)
	{
		$files = array();
		foreach (array_unique(self::$files) as $file)
		{
			if (!preg_match("/\.{$type}$/i", $file))
			{
				$file .= '.'.$type;
			}
			if (!file_exists($file))
			{
				$files = array_merge($files, glob(ASSETS_DIR.$type.'/'.$file.'*'));
			}
			else
			{
				array_push($files, $file);
			}
		}
		foreach ($files as $index=>$file)
		{
			foreach (array_unique(self::$excludes) as $pattern)
			{
				if (fnmatch($pattern, basename($file)))
				{
					unset($files[$index]);
					continue 2;
				}
			}
		}
		return $files;
	}

	private static function Reset()
	{
		self::$files = array();
		self::$excludes = array();
		self::$wrapper = '';
		self::$minify = false;
		self::$combine = false;
	}

	public static function Combine($state = true)
	{
		self::$combine = $state;
	}

	public static function Minify($state = true)
	{
		self::$minify = $state;
	}

	public static function Render($type, $filename, $template = null)
	{
		$type = strtolower($type);
		if (!isset(self::$types[ $type ]))
			throw new Exception('Triggered invalid assets type "'.$type.'"');

		$current_type = self::$types[ $type ];
		$template = is_null($template)
			? $current_type['template']
			: '%s';

		if (empty(self::$files))
			self::$files = array($filename);

		$result = '';
		if (self::$combine)
		{
			$files = self::Files($type);
			$timestamp = max(filemtime(__FILE__), max(array_map('filemtime', $files)));
			$current_filename = $filename.'.'.$timestamp.'.'.$type;
			$current_filename_min = $filename.'.'.$timestamp.'.min.'.$type;

			if (!file_exists(Cache::GetDirectory($type).'/'.$current_filename))
			{
				$content = '';
				foreach (self::Files($type) as $file)
				{
					$file_content = file_get_contents($file);
					if (isset($current_type['filter']) and is_callable($current_type['filter']))
						$file_content = call_user_func($current_type['filter'], $file_content, $file);
//					$content .= '/*! '.$file.' */'."\n".$file_content."\n";
					$content .= $file_content."\n";
				}
				if (!empty(self::$wrapper))
					$content = sprintf(self::$wrapper, $content);
				file_put_contents(Cache::GetDirectory($type).'/'.$current_filename, $content);
				file_put_contents(Cache::GetDirectory($type).'/'.$current_filename_min, self::Shrink($content, $type));
			}

			$result .= sprintf($template, 'cache/'.(self::$minify?$current_filename_min:$current_filename));
		}
		else
			foreach (self::Files($type) as $file)
			{
				$current_filename = basename($file, '.'.$type).'.'.max(filemtime(__FILE__), filemtime($file)).'.'.$type;
				$current_filename_min = basename($file, '.'.$type).'.'.max(filemtime(__FILE__), filemtime($file)).'.min.'.$type;
				if (!file_exists(Cache::GetDirectory($type).'/'.$current_filename))
				{
					$content = file_get_contents($file);

					if (isset($current_type['filter']) and is_callable($current_type['filter']))
						$content = call_user_func($current_type['filter'], $content, $file);
					if (!empty(self::$wrapper))
						$content = sprintf(self::$wrapper, $content);
					file_put_contents(Cache::GetDirectory($type).'/'.$current_filename, $content);
					file_put_contents(Cache::GetDirectory($type).'/'.$current_filename_min, self::Shrink($content, $type));
				}

				$result .= sprintf($template, 'cache/'.(self::$minify?$current_filename_min:$current_filename));
			}

		self::Reset();

		return $result;
	}

	public static function CSS($filename, $minify = false)
	{
		self::Minify($minify);
		self::Render('css', $filename);
	}

	public static function JS($filename, $minify = false)
	{
		self::Minify($minify);
		self::Render('js', $filename);
	}

	public static function Shrink($content, $type='js')
	{
		$tmp_file = '/tmp/harvest_'.md5(uniqid('random_file', true));
		file_put_contents($tmp_file, $content);
		$content = shell_exec('java -jar '.YUI_JAR.' --charset utf-8 --type '.$type.' '.$tmp_file);
		unlink($tmp_file);
		return $content;
	}

	public static function Zip($content)
	{
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')!==false)
		{
			header('Content-Encoding: gzip');
			return gzencode($content, 9);
		}
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate')!==false)
		{
			header('Content-Encoding: deflate');
			return gzdeflate($content, 9);
		}
	}

	public static function Image($filename)
	{
		$arguments = array_slice(func_get_args(), 1);
		array_unshift($arguments, 'images/'.$filename);
		return call_user_func_array(array('self', 'Img'), $arguments);
	}

	public static function Img($filename)
	{
		$arguments = array_slice(func_get_args(), 1);
		$filename = vsprintf($filename, $arguments);

		$index = hexdec(substr(md5($filename), -4)) % count($GLOBALS['ASSETS_URLS']);
		$url = $GLOBALS['ASSETS_URLS'][$index];

		return $url.$filename;
	}

}

class Assets_CSS
{
	public static function filter($content, $filename)
	{
		$content = preg_replace('/url\(\s*"\s*(.*?)\s*"\s*\)/', 'url($1)', $content);
		while ($matched = preg_match_all('/@import\s+url\(\s*(.*?)\s*\)\s*;/', $content, $matches))
		{
			for ($i=0; $i<$matched; $i++)
			{
				$import_filename = dirname($filename).'/'.$matches[1][$i];
				$imported_content = file_exists($import_filename)
					? file_get_contents($import_filename)
					: '';

				$imported_content = preg_replace('/url\(\s*"\s*(.*?)\s*"\s*\)/', 'url($1)', $imported_content);
				$content = str_replace($matches[0][$i], $imported_content, $content);
			}
		}

		$replaces = array(
			'/*[[ASSETS]]*/' => ASSETS_URL,
			'@charset "UTF-8";' => '',
		);

		$matched = preg_match_all('/\/\*\[\[ASSETS:([^]]+)\]\]\*\//', $content, $matches, PREG_SET_ORDER);
		for ($i=0; $i<$matched; $i++)
		{
			$index = hexdec(substr(md5($matches[$i][1]), -4)) % count($GLOBALS['ASSETS_URLS']);
			$url = $GLOBALS['ASSETS_URLS'][$index];

			$replaces[$matches[$i][0]] = $url.$matches[$i][1];
		}
		$content = str_replace(array_keys($replaces), array_values($replaces), $content);

		return $content;
	}
}

class Assets_JS
{
	public static function filter($content, $filename)
	{
		$replaces = array();

		$matched = preg_match_all('/@@\{(.*?)\}/', $content, $matches);
		for ($i=0, BabelFish::Get($matches[1]); $i<$matched; $i++)
		{
			$replaces[ $matches[0][$i] ] = empty($matches[1][$i])
				? BabelFish::GetLanguage()
				: addslashes(BabelFish::Get($matches[1][$i]));
		}

		$matched = preg_match_all('/%%\{(.*?)\}/', $content, $matches);
		for ($i=0; $i<$matched; $i++)
		{
			$replaces[ $matches[0][$i] ] = Assets::Image($matches[1][$i]);
		}

		$matched = preg_match_all('/&&{(.*?)\}/', $content, $matches);
		for ($i=0; $i<$matched; $i++)
		{
			$replaces[ $matches[0][$i] ] = call_user_func_array(array('FrontController', 'GetLink'), explode('/', $matches[1][$i]));
		}
		return str_replace(array_keys($replaces), array_values($replaces), $content);
	}
}
?>