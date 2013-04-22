<?php
class Dropbox
{
	private static function GetItems()
	{
		return (array) Session::Get('Dropbox');
	}

	private static function SetItems($items)
	{
		Session::Set('Dropbox', (array) $items);
	}

	public static function GetCount()
	{
		return count( self::GetItems() );
	}

	public static function Contains($id)
	{
		$items = self::GetItems();

		$result = true;
		foreach ((array)$id as $device_id)
		{
			$result = ($result and isset($items[$device_id]));
		}

		return $result;
	}

	public static function Get()
	{
		$items = self::GetItems();
		asort($items);
		return array_keys($items);
	}

	public static function Add($id)
	{
		$items = self::GetItems();

		foreach ((array)$id as $device_id)
		{
			$items[$device_id] = time();
		}

		self::SetItems( $items );
	}

	public static function Remove($id)
	{
		$items = self::GetItems();

		foreach ((array)$id as $device_id)
		{
			unset( $items[$device_id] );
		}

		self::SetItems( $items );
	}
}
?>