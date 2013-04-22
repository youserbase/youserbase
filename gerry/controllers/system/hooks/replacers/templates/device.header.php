<?php
	$image_size='large';
	$url = DBManager::Get('devices')->query('SELECT url FROM device_urls WHERE device_id = ? AND lang = ? AND tab = ?;', $device['id'], BabelFish::GetLanguage(), $tab)->fetch_item();
	if($url == null){
		$url = file_get_contents("http://tinyurl.com/api-create.php?url=".FrontController::GetUrl());
		DBManager::Get('devices')->query("INSERT INTO device_urls (url, device_id, lang, tab) VALUES(?, ?, ?, ?)", $url, $device['id'], BabelFish::GetLanguage(), $tab);
	}
	
?>
<table class="device_header">
	<colgroup>
		<col width="255px"/>
		<col/>
	</colgroup>
	<thead>
		<tr>
			<th class="r0 device_image">
				<div class="device_name">
					<h1>
						<manufacturer id="<?=$device['manufacturer_id']?>" />
						<br/>
						<phrase id="<?=$device['name']?>" />
					</h1>
				</div>
				<div class="device_actions">
						<span class="controls" rel="{url:'<?=str_replace(urlencode('%s'), '%s', FrontController::GetLink('Devices', 'ImageGallery', 'GetPicture', array('device_id'=>$device['id'],'size'=>$image_size,'index'=>'%s','limit'=>10, 'tab'=>$tab)))?>',target:'#device_image .device_image',current:'<?=$picture_index?>'}">
						<?=$this->render_partial('controls', array(
							'link'=>str_replace(urlencode('%s'), '%s', DeviceHelper::GetLink($device['id'], $device['manufacturer'], $device['name'], array('picture_id'=>'%s', 'tab'=>$tab))),
							'index'=>$picture_index,
							'max'=>max(count($device['pictures'])-1, 0),
							'phrase'=>'PICTURE',
						))?>
					</span>
					<a class="lightbox" href="<?=FrontController::GetLink('Devices', 'ImageGallery', 'Index', array('device_id'=>$device['id'], 'picture_id'=>$picture_id))?>" rel="nofollow">
						<img src="<?=Assets::Image('famfamfam/pictures.png')?>" alt_phrase="IMAGE_GALLERY" title_phrase="IMAGE_GALLERY"/>
					</a>
				<?php if (Youser::Is('god,root')): ?>
					<hr class="option_divider"/>
					<a href="<?=FrontController::GetLink('Devices', 'ImageGallery', 'Administration', array('device_id'=>$device['id']))?>">
						<img src="<?=Assets::Image('famfamfam/picture_clipboard.png')?>" alt_phrase="ADMIN_PICTURES" />
					</a>
				<?php endif; ?>
				</div>
				<div class="device_picure">
					<device id="<?=$device['id']?>" plain="true" type="<?=$image_size?>" image="<?=$picture_id?>" identifier="device_image" href="<?=FrontController::GetLink('Devices', 'ImageGallery', 'Index', array('device_id'=>$device['id'], 'picture_id'=>$picture_id))?>" link_class="lightbox"/>
				</div>
			</th>
			<th class="r1">
				<div class="device_creator_info fright">
					<div class="fright">
						<youser id="<?=$device_creator['youser_id']?>" type="avatar"/>
					</div>
					<phrase id="CREATED_BY" />
					<youser id="<?=$device_creator['youser_id']?>"/>
					<br />
					<phrase id="ON"/>
					<?=dateformat($device_creator['timestamp'])?>
				</div>
				<div class="requests">
					<em><?=numberformat($requests['absolute'])?></em>
					<phrase id="<?=$requests['absolute']==1?'TIME_REQUESTED':'TIMES_REQUESTED'?>"/>
					<phrase id="BY"/>
					<em><?=numberformat($requests['relative'])?></em>
					<phrase id="<?=$requests['relative']==1?'YOUSER':'YOUSERS'?>"/>
				</div>
			<?php if(in_array(BabelFish::GetLanguage(), array('de', 'uk', 'us', 'se', 'nl', 'fr', 'es', 'it', 'se', 'no', 'dk', 'ro')) or Youser::Is('administrator,root,god')): ?>
				<div class="info_text" style="border: 1px dotted #ccc; border-width: 1px 0; padding: 5px; margin: 10px 0;">
					<?=$this->render_partial('seo_text', array('device_id'=>$device['id'], 'data' => $sheet['common']))?>
				</div>
<?php elseif (isset($rating)): ?>
				<hr class="option_divider" />
			<?php endif;?>
			<?php if (isset($rating)): ?>
				<div class="device_rating fleft">
					<?=$this->render_partial('rating', array('name'=>'device_rating', 'rating'=>$rating['device_rating'], 'device_id' => $device['id'], 'sheet_tab' => $tab, 'tab' => 'device_rating', 'feature' => 'null', 'table' => 'null', 'dimension' => 'big'))?>
					<?php if (isset($rating, $number_of_ratings)): ?>
						<div class="rating_count">
							<em><?=numberformat($number_of_ratings)?></em>
							<phrase id="<?=$number_of_ratings==1?'TIME_RATED':'TIMES_RATED'?>"/>
						</div>
					<?php endif; ?>
					<?php if(isset($prices)):?>
						<?=$this->render_partial('priceinfo', compact('prices'))?>
					<?php endif;?>
				</div>
				<div class="social fright">
				<p>
					<a href="http://twitter.com/home?source=youserbase.org&status=From%20@youserbase:%20<?=BabelFish::Get($device['manufacturer'])?> <?=BabelFish::Get($device['name'])?> <?= $url?>" target="_blank">
					<img src="<?=Assets::Image('Twitter.png')?>" alt="Tweet_This" height="30px">weet
					</a>
				</p>
				<p>
				<fb:like href="<?=str_replace('/'.$tab, '', FrontController::GetUrl())?>" layout="button_count" show_faces="true" width="160" action="like" colorscheme="light">
				</fb:like>
				</p>
			</div>
			<div class="clr"></div>
			<?php endif; ?>
				<div class="device_controls">
					<hr class="option_divider"/>
					<a class="ajax target:#dropbox_count sprite front attach" href="<?=FrontController::GetLink('Dock', 'Dropbox', 'Add', array('id'=>$device['id'], 'return_to'=>FrontController::GetLink()))?>" title_phrase="ADD_TO_DROPBOX" rel="nofollow">
						 <phrase id="COMPARE"/>
					</a>
					|
					<a class="lightbox sprite front page_copy" href="<?=FrontController::GetLink('datasheets', 'initCopyDevice', array('device_id'=>$device['id'], 'model' => $device['name'], 'main_type' => $main_type))?>" rel="nofollow">
						<phrase id="COPY_DEVICE"/>
					</a>
			<?php if(Youser::Id()):?>
					<hr class="option_divider"/>
					<a href="<?=FrontController::GetLink('Devices', 'ImageGallery', 'AddImage', array('device_id'=>$device['id']))?>" class="lightbox sprite front picture_add" rel="nofollow">
						<phrase id="ADD_PICTURE"/>
					</a>
				<?php if(Youser::Is('god', 'root', 'administrator')):?>
					|
					<a href="<?=FrontController::GetLink('deletedevice', array('device_id' => $device['id']))?>" class="confirm sprite front delete" title_phrase="DELETE_DEVICE" rel="nofollow">
						<phrase id="DELETE_DEVICE"/>
					</a>
				<?php endif;?>
<?php /*
					<a href="<?=FrontController::GetLink('sync', array('device_id' => $device['id']))?>" class="lightbox">
						<img src="<?=Assets::Image('famfamfam/arrow_refresh.png')?>" alt_phrase="SYNC"/> <phrase id="SYNC_DEVICE"/>
					</a>
*/ ?>
			<?php endif; ?>
				</div>
			</th>
		</tr>
	</thead>
</table>