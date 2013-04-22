<?php
	echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n";
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<atom:icon><?=FrontController::GetAbsoluteURI()?><?=Assets::Image('youserbase_logo_topwest.gif')?></atom:icon>
        <title><?=$device_name?></title>
        <atom:link href="<?=FrontController::GetAbsoluteURI()?>Device/<?=$device_id?>/photos.rss" rel="self" type="application/rss+xml"/>
        <link><?=FrontController::GetAbsoluteUri()?><?=$device_link?></link>
        <description>foobar</description>
       	<?php foreach ($images as $image): ?>
		<item>
			<guid><?=FrontController::GetAbsoluteUri()?><?=DeviceHelper::GetImage($device_id, 'original', $image['device_pictures_id'])?></guid>
			<title><?=$device_name?></title>
			<media:description><?=$image['original_filename']?></media:description>
			<link><?=FrontController::GetAbsoluteUri()?><?=$device_link?></link>
			<media:thumbnail url="<?=FrontController::GetAbsoluteUri()?><?=DeviceHelper::GetImage($device_id, 'small', $image['device_pictures_id'])?>"/>
			<media:content url="<?=FrontController::GetAbsoluteUri()?><?=DeviceHelper::GetImage($device_id, 'large', $image['device_pictures_id'])?>"/>
		</item>
	<?php endforeach; ?>
	</channel>
</rss>
