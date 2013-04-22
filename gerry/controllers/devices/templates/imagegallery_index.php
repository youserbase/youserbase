<div id="device_image_gallery" rel="{link:'<?=FrontController::GetLink('GetImages', array('device_id'=>$device['id']))?>',page:<?=$page?>,max_pages:<?=$max_pages?>}">
	<div class="controls">
		<a class="page<?=$page?> previous <?=$page==0?'disabled':''?>" href="<?=FrontController::GetLink(array('device_id'=>$device['id'], 'picture_id'=>$id, 'page'=>max($page-1,0)))?>">
			<img src="<?=Assets::Image('famfamfam/reverse_blue.png')?>" alt_phrase="BACK"/>
		</a>
	<?php foreach (array_slice($pictures, $page*$limit, $limit, true) as $picture_id=>$images): ?>
		<a class="page<?=$page?> image <?=$picture_id==$id?'current':''?>" href="<?=FrontController::GetLink(array('device_id'=>$device['id'], 'picture_id'=>$picture_id, 'page'=>$page))?>" rel="<?=$picture_id?>">
			<img src="<?=$images['thumb']?>" alt=""/>
		</a>
	<?php endforeach; ?>
		<a class="page<?=$page?> next <?=$page==$max_pages?'disabled':''?>" href="<?=FrontController::GetLink(array('device_id'=>$device['id'], 'picture_id'=>$id, 'page'=>min($page+1,$max_pages)))?>">
			<img src="<?=Assets::Image('famfamfam/play_blue.png')?>" alt_phrase="FORWARD"/>
		</a>
	</div>
	<div class="image">
		<img src="<?=$pictures[ $id ]['image']?>" alt=""/>
	</div>
</div>
