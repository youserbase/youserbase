<div style="float: left; margin-right: 5px;">
	<youser id="<?=$youser_id?>" image="small"/>
</div>
<youser id="<?=$youser_id?>" <?=empty($needle)?'':'highlight="'.$needle.'"'?>/>
<?php if (!empty($first_name) and !empty($last_name)): ?>
, <?=$first_name?> <?=$last_name?>
<?php endif; ?>
<br style="clear: left;"/>