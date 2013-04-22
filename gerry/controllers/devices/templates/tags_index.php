<div class="rbox">
	<h3><phrase id="TAGS"/></h3>

	<div style="width: 33%; float: left;">
		<h4><phrase id="POPULAR_TAGS"/>:</h4>
		<ul>
		<?php foreach ($popular_tags as $tag => $quantity): ?>
			<li><a href="<?=FrontController::GetLink('Tag', array('tag'=>$tag))?>"><?=$tag?></a> (<?=numberformat($quantity)?>)</li>
		<?php endforeach; ?>
		</ul>
	</div>

	<div style="width: 33%; float: left;">
		<h4><phrase id="LATEST_TAGS"/>:</h4>
		<ul>
		<?php foreach ($latest_tags as $tag => $timestamp): ?>
			<li><a href="<?=FrontController::GetLink('Tag', array('tag'=>$tag))?>"><?=$tag?></a> <?=twittertime($timestamp)?></li>
		<?php endforeach; ?>
		</ul>
	</div>

	<div style="width: 33%; float: left;">
		<h4><phrase id="MOST_TAGGED_DEVICES"/>:</h4>
		<ul>
		<?php foreach ($most_tagged_devices as $device_id => $quantity): ?>
			<li><device id="<?=$device_id?>"/> (<?=numberformat($quantity)?>)</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>