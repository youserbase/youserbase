<?php
require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
require dirname(__FILE__).'/../classes/ClassLoader.class.php';
require dirname(__FILE__).'/../includes/classloader.inc.php';
require dirname(__FILE__).'/../includes/config.inc.php';
require dirname(__FILE__).'/../includes/functions.inc.php';

$devices = DBManager::Get('devices')->query("SELECT DISTINCT(device_id) FROM device_statistics")->to_array(null, 'device_id');
$months = array(1, 2, 3);
foreach ($devices as $device_id)
{
	foreach ($months as $month)
	{
		$from = date('Y').'-'.(date('m')-$month).'-00 00:00:00';
		$till = date('Y').'-'.(date('m')-$month+1).'-31 00:00:00';
		$rating = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(youser_id)) as impact FROM device_statistics WHERE timestamp > ? AND timestamp < ? AND device_id = ? GROUP BY device_id ORDER BY COUNT(DISTINCT(youser_id)) DESC", $from, $till, $device_id)->fetch_item();
		$impact[$month] = $rating/($month*$month);
	}
	$device_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
	$impact_sum = array_sum($impact);
	DBManager::Get('devices')->query("INSERT DELAYED INTO device_statistics_tmp (device_id, device_id_int, impact) VALUES(?,?,?) ON DUPLICATE KEY UPDATE impact=VALUES(impact), timestamp = CURRENT_TIMESTAMP;", $device_id, $device_int, $impact_sum);
}
unset($devices);
?>