<?php
class MemoryShelf
{
	const MODE = 'xcache'; // For the moment, it's just xcache

	private static $default_time_to_live = 86400; // 24 * 60 * 60
	private $data,
		$scope,
		$time_to_live;

	public static function Pick($scope, $time_to_live = null)
	{
		if (is_null($time_to_live))
			$time_to_live = self::$default_time_to_live;
		return new self($scope, $time_to_live);
	}

	private function __construct($scope, $time_to_live)
	{
		$this->scope = $scope;
		$this->time_to_live = $time_to_live;

		$this->data = xcache_get($scope);
		if (empty($this->data))
			$this->data = array();
		else
			$this->data = unserialize($this->data);
	}

	public function put($index, $value = null)
	{
		if (func_num_args()==1 and is_array($index))
			$this->data = array_merge($this->data, $index);
		else
			$this->data[$index] = $value;
		xcache_set($this->scope, serialize($this->data), $this->time_to_live);
	}

	public function get($index)
	{
		if (is_array($index))
		{
			$result = array();
			foreach ($index as $id)
				$result[$id] = $this->get($id);
			return $result;
		}

		return isset($this->data[$index])
			? $this->data[$index]
			: null;
	}

	public function clear()
	{
		$this->data = array();
		xcache_set($this->scope, serialize($this->data), $this->time_to_live);
	}

	public function get_keys()
	{
		return array_keys($this->data);
	}
}
?>