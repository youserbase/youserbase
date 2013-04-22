<?php
/**
 * @package youserbase
 * @subpackage core
 */
class Dobber
{
	const max_per_type = 10;

	private static function ReportMessage($type, $message)
	{
		$arguments = array_slice(func_get_args(), 2);

		$dobber = Session::Get('Dobber', $type);
		if (empty($dobber))
			$dobber = array();

		if (strpos($message, ' ')!==false)
			array_push($dobber, vsprintf($message, $arguments));
		else
			array_push($dobber, array(
				'message' => $message,
				'parameters' => reset($arguments),
			));

		$dobber = array_slice($dobber, -self::max_per_type);
		Session::Set('Dobber', $type, $dobber);
	}

	public static function ReportSuccess($message)
	{
		$arguments = func_get_args();
		array_unshift($arguments, 'success');

		call_user_func_array(array('self', 'ReportMessage'), $arguments);
	}

	public static function ReportNotice($message)
	{
		$arguments = func_get_args();
		array_unshift($arguments, 'notice');

		call_user_func_array(array('self', 'ReportMessage'), $arguments);
	}

	public static function ReportWarning($message)
	{
		$arguments = func_get_args();
		array_unshift($arguments, 'warning');

		call_user_func_array(array('self', 'ReportMessage'), $arguments);
	}

	public static function ReportError($message)
	{
		$arguments = func_get_args();
		array_unshift($arguments, 'error');

		call_user_func_array(array('self', 'ReportMessage'), $arguments);
	}

	public static function ReportSystemError($message)
	{
		$arguments = func_get_args();
		array_unshift($arguments, 'system_error');

		call_user_func_array(array('self', 'ReportMessage'), $arguments);
	}

	public static function getMessages($type=null, $exclude_type=null)
	{
		$messages = $type===null
			? (array)Session::GetAndClear('Dobber')
			: (array)Session::GetAndClear('Dobber', $type);
		if ($type===null and $exclude_type!==null and isset($messages[$exclude_type]))
		{
			Session::Set('Dobber', $exclude_type, $messsages[$exclude_type]);
			unset($messages[$exclude_type]);
		}
		return $messages;
	}

	public static function IsEmpty($type)
	{
		return Session::Get('Dobber', $type)===false or !is_array(Session::Get('Dobber')) or count(Session::Get('Dobber'))===0;
	}

	public static function __callStatic($method, $arguments)
	{
		echo '<pre>',var_dump($method),var_dump($arguments),'</pre>';
	}
}
?>