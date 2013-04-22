<?php
	$image_size='large';
	$url = DBManager::Get('devices')->query('SELECT url FROM device_urls WHERE device_id = ? AND lang = ? AND tab = ?;', $device['id'], BabelFish::GetLanguage(), $tab)->fetch_item();
	if($url == null){
		$url = file_get_contents("http://tinyurl.com/api-create.php?url=".FrontController::GetUrl());
		DBManager::Get('devices')->query("INSERT INTO device_urls (url, device_id, lang, tab) VALUES(?, ?, ?, ?)", $url, $device['id'], BabelFish::GetLanguage(), $tab);
	}
	
?>
<div class="device_header type_small clearfix" style="margin-bottom: 5px;">
	<div class="fleft">
		<device id="<?=$device['id']?>" type="avatar" plain="true" rating="true"/>
	</div>
	<div class="fleft">
	<h1><device id="<?=$device['id']?>" /></h1>
	<h2><?=$title?></h2>
	</div>
	<div class="socialise fright">
		<p>
			<a href="http://twitter.com/home?source=youserbase.org&status=From%20@youserbase:%20<?=BabelFish::Get($device['manufacturer'])?> <?=BabelFish::Get($device['name'])?> <?= $url?>" target="_blank">
				<img src="<?=Assets::Image('Twitter.png')?>" alt="Tweet_This" height="30px">weet
			</a>
		</p>
		<?php if(Youser::Is('administrator', 'root', 'god')):?>
			<meta property="og:image" content="<?=DeviceHelper::GetImage($device['id'], 'avatar')?>"/>
		<?php endif;?>
		<fb:like href="<?=str_replace('/'.$tab, '', FrontController::GetUrl())?>" layout="button_count" show_faces="true" width="160" action="like" colorscheme="light" />
	</div>
	<div class="clr"></div>
</div>