<?='<?xml'?> version="1.0" encoding="UTF-8"?>
<!-- Sitemap generated: <?=date('Y-m-d H:i:s')?>-->
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($languages as $language): ?>
	<sitemap>
		<loc><?=FrontController::GetAbsoluteURI()?>sitemap.<?=$language?>.xml</loc>
	</sitemap>
<?php endforeach; ?>
</sitemapindex>
