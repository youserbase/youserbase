<?php if(!empty($devices)):?>
<phrase id="SELECT_DEVICE"/>
	<select name="source_device_id">
	<?php foreach ($devices as $device_id => $device_name):?>
		<option value="<?=$device_id?>">
			<phrase id="<?=$device_name?>"/>
		</option>
	<?php endforeach;?>
	</select>
<?php else:?>
	<phrase id="NO_DEVICES"/>
<?php endif;?>
