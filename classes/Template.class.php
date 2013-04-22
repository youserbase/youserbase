<?php
class Template
{
	protected $filename;
	protected $variables = array();
	protected $layout;
	protected $layout_position = null;
	protected $filters = array();
	protected $blank = false;
	protected $content = null;
	protected $rendered = false;

	public function __construct($filename, $layout = false)
	{
		if ($this->blank = ($filename === false))
		{
			$this->content = ($layout === false)
				? ''
				: $layout;
		}
		else
		{
			$this->filename = $filename;
			$this->layout = $layout;
		}
	}

	public function register_filter($filter_name)
	{
		$arguments = func_get_args();

		$this->filters[$filter_name] = array_slice($arguments, 1);

		return $this;
	}

	public function assign($variable, $value = null)
	{
		if (is_array($variable) and $value === null)
			$this->set_variables($variable);
		elseif (is_array($variable))
			$this->set_variables(array_combine($variable, array_fill(0, count($variable), $value)));
		else
			$this->variables[$variable] = $value;

		return $this;
	}

	public function set_variables($variables)
	{
		$this->variables = array_merge($this->variables, $variables);
		return $this;
	}

	public function get_variable($key)
	{
		return isset($this->variables[$key])
			? $this->variables[$key]
			: null;
	}

	public function get_variables()
	{
		return $this->variables;
	}

	public function evaluate($string, $url_encoded = false)
	{
		return self::Interpolate($string, $this->variables, $url_encoded);
	}

	public function set_layout($layout)
	{
		$this->layout = $layout;
		return $this;
	}

	public function set_position($position = null)
	{
		$this->layout_position = $position;
	}

	public function remove_layout()
	{
		$this->layout = null;
		return $this;
	}

	public function has_layout()
	{
		return !empty($this->layout);
	}

	public function append($variable, $value)
	{
		if (!isset($this->variables[$variable]))
		{
			$this->variables[$variable] = $value;
		}
		elseif (is_array($this->variables[$variable]))
		{
			$this->extend($variable, $value);
		}
		elseif (is_numeric($this->variables[$variable]))
		{
			$this->variables[$variable] += $value;
		}
		else
		{
			$this->variables[$variable] .= $value;
		}
		return $this;
	}

	public function extend($variable, $value)
	{
		if (!isset($this->variables[$variable]))
		{
			$this->variables[$variable] = array();
		}
		elseif (!is_array($this->variables[$variable]))
		{
			$this->variables[$variable] = array($this->variables[$variable]);
		}
		array_push($this->variables[$variable], $value);

		return $this;
	}

	private function _render($variables, $filename)
	{
		$__original_filename = $filename;

		if (!file_exists($filename) and strpos($filename, '.php') === false)
		{
			$filename .= '.php';
		}
		if (!file_exists($filename))
		{
			$filename = './templates/'.basename($filename);
		}

		if (file_exists($filename))
		{
			extract($variables);
			ob_start();
			include $filename;
			$content = ob_get_clean();
		}
		else
		{
			$content = sprintf('Could not find template file "%s"', $__original_filename);
		}

		return $content;
	}

	public function render_partials($filename, $array, $parameters = array())
	{
		$result = '';
		foreach (array_values($array) as $index => $item)
		{
			$item = array_merge($parameters, $item);
			$result .= $this->render_partial($filename, $item, $index);
		}
		return $result;
	}

	public function render_partial($filename, $variables = array(), $index = null)
	{
/*
		$variables = array_merge($this->variables, $variables);
*/
		if ($index !== null)
		{
			$variables = array_merge( array('partial_index'=>$index), $variables);
		}

		$partial_file = ($filename .= '.partial');
		if (count(glob($partial_file.'.*', GLOB_NOSORT)))
		{
			return $this->_render($variables, $partial_file);
		}

		$partial_file = dirname($this->filename).'/'.$filename;
		if (count(glob($partial_file.'.*', GLOB_NOSORT)))
		{
			return $this->_render($variables, $partial_file);
		}

		$partial_file = dirname($this->filename).'/'.basename($this->filename, '.php').'.'.$filename;
		if (count(glob($partial_file.'.*', GLOB_NOSORT)))
		{
			return $this->_render($variables, $partial_file);
		}

		$partial_file = './templates/'.$filename;
		if (count(glob($partial_file.'.*', GLOB_NOSORT)))
		{
			return $this->_render($variables, $partial_file);
		}

		if ($this->layout)
		{
			$partial_file = dirname($this->layout).'/'.basename($this->layout, '.php').'.'.$filename;
			if (count(glob($partial_file.'.*', GLOB_NOSORT)))
			{
				Timer::Report('Template 5');
				return $this->_render($variables, $partial_file);
			}
		}

		$partial_file = $filename;
		return $this->_render($variables, $partial_file);
	}

	public function render()
	{
		if (!$this->blank)
		{
			Timer::Report('[TEMPLATE:%s] Before render', $this->filename);
			$this->content = $this->_render($this->variables, $this->filename);
			Timer::Report('[TEMPLATE:%s] After render', $this->filename);
		}

		if ($this->layout)
		{
			$variables = array_merge($this->variables, array(
				'CONTENT' => $this->content,
				'POSITION' => $this->layout_position,
			));
			$this->content = $this->_render($variables, $this->layout);
		}

		if (class_Exists('Event'))
		{
			Timer::Report('[TEMPLATE:%s] Before shape', $this->filename);
			$this->content = Event::Dispatch('shape', 'Template:Rendered', $this->content);
			Timer::Report('[TEMPLATE:%s] After shape', $this->filename);
		}

		$this->rendered = true;

		return $this;
	}

	public function parse()
	{
		Timer::Report('[TEMPLATE:%s] Before parse', $this->filename);
		if (!$this->rendered)
		{
			$this->render();
		}
		Timer::Report('[TEMPLATE:%s] After parse', $this->filename);

		return $this->content;
	}

	public function fetch($text_only = false)
	{
		$this->parse();

		if (class_exists('Event'))
		{
			$this->content = Event::Dispatch('shape', 'Template:Fetch', $this->content, $text_only);
		}
		return $this->content;
	}

	public function display()
	{
		$arguments = func_get_args();
		call_user_func_array(array($this, 'fetch'), $arguments);

		if (class_exists('Event'))
		{
			$this->content = Event::Dispatch('shape', 'Template:Display', $this->content);
		}

		$this->execute_filters();

		print $this->content;
	}

	public function execute_filters()
	{
		foreach ($this->filters as $filter=>$arguments)
		{
			include_once dirname(__FILE__).'/plugins/filter.'.$filter.'.php';
			array_unshift($arguments, $this->content);
			$this->content = call_user_func_array('filter_'.$filter, $arguments);
		}
	}

	public function __toString()
	{
		return $this->content;
	}

	public static function Interpolate($string, $parameters, $url_encoded = false)
	{
		if (is_array($string))
		{
			return 'Invalid call';
		}

		$replaces = array();
		foreach ((array)$parameters as $key => $value)
		{
			if (strpos($string, '#{'.$key) === false)
			{
				continue;
			}

			if (is_object($value))
			{
				$value = get_object_vars($value);
			}
			elseif (!is_array($value))
			{
				$value = array(-1 => $value);
			}

			foreach ($value as $index => $data)
			{
				if (is_array($data))
				{
					continue;
				}
				$idx = '#{'.$key.($index!=-1 ? '.'.$index : '').'}';
				$replaces[$idx] = $url_encoded
					? urlencode($data)
					: $data;

				/** Ugly hack for now **/
				$idx = '#{'.$key.($index!=-1 ? '.'.$index : '').'+1}';
				$replaces[$idx] = $url_encoded
					? urlencode($data + 1)
					: $data + 1;
			}
		}
		return str_replace(array_keys($replaces), array_values($replaces), $string);
	}
}
?>