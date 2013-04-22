<?php if(isset($count)) $count = 1 + $skip; ?>
<ul class="floated device_list <?=!empty($narrow)?'narrow':''?>">
<?php foreach ($devices as $id => $value): ?>
	 <li>
	<?php if (isset($count)): ?>
		<span class="counter"><?=$count++?></span>
	<?php endif; ?>
		<device id="<?=$id?>" type="avatar" <?=!empty($rating)?'rating="true"':''?>/>
	</li>
<?php endforeach;?>
</ul>