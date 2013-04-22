<?php
	require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
	require dirname(__FILE__).'/../classes/ClassLoader.class.php';
	require dirname(__FILE__).'/../includes/classloader.inc.php';
	require dirname(__FILE__).'/../includes/config.inc.php';
	require dirname(__FILE__).'/../includes/functions.inc.php';

	header('Content-Type: text/plain');

	$cronjobs = PluginEngine::GetCrons();

	$semaphores = DBManager::Get('cache')->query("SELECT plugin_id, timestamp FROM plugin_semaphores WHERE plugin_id IN (?)", array_keys($cronjobs))->to_array('plugin_id', 'timestamp');

	foreach ($cronjobs as $id => $data)
	{
		if (isset($semaphores[$id]) and $semaphores[$id] + $data['interval'] > time())
		{
			continue;
		}
		$plugin = PluginEngine::GetPlugin($data['name']);
		$plugin->cronjob();

		DBManager::Get('cache')->query("INSERT INTO plugin_semaphores (plugin_id, timestamp) VALUES (?, UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE timestamp=VALUES(timestamp)", $id);
	}
?>
