<?php

	require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
        require dirname(__FILE__).'/../classes/ClassLoader.class.php';
        require dirname(__FILE__).'/../includes/classloader.inc.php';
        require dirname(__FILE__).'/../includes/config.inc.php';
        require dirname(__FILE__).'/../includes/functions.inc.php';

	DBManager::Get('devices')->query('INSERT INTO online (id, yousers_online, timestamp) SELECT 1, COUNT(DISTINCT(youser_id)), NOW()  FROM device_statistics WHERE TIMESTAMPADD(MINUTE, 5, timestamp)>NOW() ON DUPLICATE KEY UPDATE id=VALUES(id), yousers_online=VALUES(yousers_online), timestamp=VALUES(timestamp);');


?>