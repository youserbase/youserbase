<?='<?xml'?> version="1.0" encoding="UTF-8"?>
<!-- Sitemap generated: <?=date('Y-m-d H:i:s')?>-->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('System', 'System', 'Index')?></loc>
	</url>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('Devices', 'Consultant', 'Index')?></loc>
	</url>
<?php // KATALOG ** ?>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('Datasheets', 'Catalogue', 'Index')?></loc>
	</url>
<?php foreach ($manufacturers as $manufacturer_id=>$manufacturer): ?>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('Datasheets', 'Catalogue', 'Manufacturer', array('manufacturer_id'=>$manufacturer_id))?></loc>
	</url>
	<?php foreach (Device::GetByManufacturerId($manufacturer_id) as $device_id=>$device): ?>
		<url>
			<loc><?=FrontController::GetAbsoluteURI()?><?=DeviceHelper::GetLink($device_id)?></loc>
		</url>
		<?php foreach (array_slice(array_keys(sheetConfig::$mobilephone_sheet), 1) as $tab): ?>
		<url>
			<loc><?=FrontController::GetAbsoluteURI()?><?=DeviceHelper::GetLink($device_id, null, null, array('tab'=>$tab))?></loc>
		</url>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endforeach; ?>

<?php // PAGES *** ?>
<?php foreach (array('HELP', 'FAQ', 'MASTHEAD', 'TOS', 'ADVERTISING', 'PRIVACY_POLICY', 'ABOUT', 'CONTACT') as $page): ?>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('System', 'Pages', 'Display', array('page'=>$page))?></loc>
	</url>
<?php endforeach; ?>


<?php // USER *** ?>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=FrontController::GetLink('User', 'Profile', 'Index')?></loc>
	</url>
<?php foreach (Youser::GetBundle() as $youser_id=>$youser): ?>
	<?php if (!$youser['visible']) continue; ?>
	<url>
		<loc><?=FrontController::GetAbsoluteURI()?><?=YouserHelper::GetLink($youser['youser_id'], $youser['nickname'])?></loc>
	</url>
<?php endforeach; ?>

</urlset>