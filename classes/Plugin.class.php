<?php
abstract class Plugin
{
	abstract function fill_template(&$template);

	private $_name,
		$_method,
		$_directory,
		$_template_directory,
		$_link;

	public function __construct($name, $method, $plugin_directory, $link)
	{
		$this->_name = $name;
		$this->_method = $method;
		$this->_directory = $plugin_directory;
		$this->_template_directory = $this->_directory.'/templates';
		$this->_link = $link;
	}

	public function &get_template()
	{
		$filename = $this->_template_directory.'/'.strtolower($this->_name).'_'.strtolower($this->_method).'.php';
		if (!file_exists($filename))
		{
			$filename = $this->_template_directory.'/'.strtolower($this->_name).'.php';
		}
		if (!file_exists($filename))
		{
			throw new Exception('Could not find plugin template');
		}

		$template = new Template($filename);

		return $template;
	}

	public function get_config($key)
	{
		return PluginEngine::GetConfig($this->_name, $key);
	}

	public function get_link()
	{
		return $this->_link;
	}

	public function set_link($link)
	{
		$this->_link = $link;

		return $this;
	}

	public function has_header()
	{
		return true;
	}

	public function has_wrapper()
	{
		return true;
	}

}
?>