<?php
	require_once '../includes/bootstrap.inc.php';

	$cache_filename = sprintf('%s/sitemap.%s%u.xml',
		Cache::GetDirectory('sitemap'),
		empty($_GET['language']) ? '' : $_GET['language'].'.',
		date('Ymd')
	);

	if (!file_exists($cache_filename))
	{
		ob_start(create_function('$a', 'return "";')); // #1 Avoid any unneccessary whitespace, invalidating the sitemap
		Event::RegisterHooks(
			'../includes/hooks',
			'./controllers/*/hooks',
			'./controllers/*/plugins'
		);
		ob_end_clean(); // #1 Closed

		$old_language = BabelFish::GetLanguage();

		if (empty($_GET['language']))
		{
			$template = new Template('templates/sitemap_index');
			$template->assign('languages', BabelFish::GetLanguages());
		}
		else
		{
			BabelFish::SetLanguage($_GET['language']);
			$template = new Template('templates/sitemap');
			$template->assign('manufacturers', Manufacturer_Name::GetAll());
		}
		$template->register_filter('stripwhitespace');
		$contents = $template->render();

		file_put_contents($cache_filename, $contents);

		BabelFish::SetLanguage($old_language);
	}
	else
		$contents = file_get_contents($cache_filename);

	Header('Content-Type: text/xml; Charset=utf-8');
	echo $contents;
?>