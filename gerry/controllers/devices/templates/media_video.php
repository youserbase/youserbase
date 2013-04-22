<?php
	$width=576;
	$video = count($videos) ? $videos[$current] : false;
?>
<?php if (!$video): ?>
<device id="<?=$device_id?>" header="small" tab="<?=$tab?>" />
<p><phrase id="NO_MEDIA" /></p>
<?php else: ?>

<device id="<?=$device_id?>" header="small" tab="<?=$tab?>" title="<?=BoxBoy::Prepare($video['title'])?>" />

<?php if (count($videos)>1): ?>
<div class="fright rbox">
	<h1 class="blue" style="margin-bottom:0;"><phrase id="MORE_VIDEOS" /></h1>
	<div class="content">
		<ul class="small zebra videos" style="width: <?=712-$width?>px; max-height: 400px; overflow-x: hidden; overflow-y: auto;">
		<?php $index=0; foreach (array_values($videos) as $v): ?>
			<?php if ($v['media_id']==$current) continue; ?>
			<li class="a<?=$index%2?> r<?=$index++?>">
				<p class="title">
					<a class="plain" href="<?=FrontController::GetLink('Datasheets', 'datasheets', 'page', array('device_id'=>$device_id, 'tab'=>$tab,'media_id'=>$v['media_id']))?>" title="<?=$v['title']?>">
						<?=str_truncate($v['title'], 24)?>
					</a>
				</p>
				<p class="content">
					<?php if ($v['source']=='youtube'): ?>
					<a class="plain youtube v90" href="<?=FrontController::GetLink('Datasheets', 'datasheets', 'page', array('device_id'=>$device_id, 'tab'=>$tab,'media_id'=>$v['media_id']))?>">
						<img src="http://i.ytimg.com/vi/<?=$v['media_key']?>/default.jpg" class="youtube image v90" alt="" />
					</a>
					<?php endif; ?>
				</p>
				<p class="time">
					<?=twittertime($v['media_timestamp'])?>
				</p>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php endif; ?>

<div class="video" style="width:<?=$width+5?>px;">
	<?php if ($video['source']=='youtube'): ?>
	<object width="<?=$width?>" height="<?=$width/640*385?>">
		<param name="movie" value="http://www.youtube.com/v/<?=$video['media_key']?>&hl=de_DE&fs=1&"></param>
		<param name="allowFullScreen" value="true"></param>
		<param name="allowscriptaccess" value="always"></param>
		<embed src="http://www.youtube.com/v/<?=$video['media_key']?>&hl=de_DE&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?=$width?>" height="<?=$width/640*385?>"></embed>
	</object>
	<?php endif; ?>
	<div class="meta">
		<phrase id="TITLE" />: <strong><?=BoxBoy::Prepare($video['title'])?></strong><br />
		<phrase id="BY" /> <?=BoxBoy::Prepare($video['author'])?> (<?=dateformat($video['media_timestamp'])?>)<br />
		<div class="right">
			<phrase id="ADDED_BY" />: <youser id="<?=$video['user_id']?>" /> (<?=dateformat($video['timestamp'])?>)
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (Youser::Is('god','root','admin') or Youser::May('edit_media')): ?>
<div class="admin_panel rbox">
	<a class="button add lightbox" href="<?=FrontController::GetLink('Add', compact('device_id'))?>" class="lightbox"><phrase id="ADD_VIDEO" /></a>
<?php if ($video): ?>
	<a class="button edit lightbox" style="float: none;" href="<?=FrontController::GetLink('Edit', array('device_id'=>$device_id, 'media_id'=>$current))?>"><phrase id="EDIT_VIDEO" /></a>
	<a class="button delete confirm" href="<?=FrontController::GetLink('Delete', array('device_id'=>$device_id, 'media_id'=>$current))?>" title_phrase="DELETE_VIDEO"><phrase id="DELETE_VIDEO" /></a>
<?php endif; ?>
</div>
<?php endif; ?>

