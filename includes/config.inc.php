<?php
	$path = dirname(__FILE__);

	define('YB_VERSION', '1.1&beta; (20090722)');

	define('CACHE_DIR', $path.'/../cache/');
	define('APP_DIR', realpath($path.'/../gerry/'));
	define('INC_DIR', realpath($path.'/../includes/'));
	define('YUI_JAR', realpath($path.'/../classes/vendor/java/').'/yuicompressor-2.4.2.jar');
	define('DEFAULT_GFX_FORMAT', 'png');

	define('ASSETS_DIR', APP_DIR.'/assets/');
	define('ASSETS_IMAGE_DIR', '/var/www/yb_assets/');
	define('ASSETS_URL', 'http://assets.youserbase.org/');
	$GLOBALS['ASSETS_URLS'] = array(
		'http://assets0.youserbase.org/',
		'http://assets1.youserbase.org/',
		'http://assets2.youserbase.org/',
		'http://assets3.youserbase.org/',
//		'http://assets4.youserbase.org/',
//		'http://assets5.youserbase.org/',
//		'http://assets6.youserbase.org/',
//		'http://assets7.youserbase.org/',
//		'http://assets8.youserbase.org/',
//		'http://assets9.youserbase.org/',
	);

	define('DB_CONNECTION_STRING', 'mysql://youserbase:efh7tv86@dbserver/fixed_state_1?encoding=UTF8');
	define('DB_CONNECTION_STRING_DEVICES', 'mysql://youserbase:efh7tv86@dbserver/fixed_state_2?encoding=UTF8');
	define('DB_CONNECTION_STRING_CACHE', 'mysql://youserbase:efh7tv86@dbserver/webcache?encoding=UTF8');

    // Initialize dbs
   	DBManager::Set('default', DB_CONNECTION_STRING);
	DBManager::Set('devices', DB_CONNECTION_STRING_DEVICES);
	DBManager::Set('cache', DB_CONNECTION_STRING_CACHE);
?>
