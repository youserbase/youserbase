<div class="more"><img src="<?=Assets::Image('famfamfam/help.png')?>" alt="info"/></div>

<youser id="<?=$object_id?>" type="avatar"/>
<device id="<?=$subject_id?>" type="avatar"/>

<youser id="<?=$object_id?>"/> <phrase id="<?=count((array)$scope)>1?'ACTIVITY_IMAGES_ADD':'ACTIVITY_IMAGE_ADD'?>" count="<?=count((array)$scope)?>"/> <device id="<?=$subject_id?>"/>

<blockquote>
<?php foreach ((array)$scope as $item): ?>
	<device id="<?=$subject_id?>" type="avatar" image="<?=$item?>"/>
<?php endforeach; ?>
</blockquote>