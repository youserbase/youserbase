<div class="rbox">
	<form id="image_editor" action="<?=FrontController::GetLink()?>" method="post" class="yform columnar <?=$VIA_AJAX?'ajax lightbox':''?>">
		<fieldset>
		<?php if ($previous): ?>
			<a class="prev <?=$VIA_AJAX?'lightbox':''?>" href="<?=FrontController::GetLink(array('device_id'=>$picture['device_id'], 'id'=>$previous))?>">prev</a>
		<?php endif; ?>
		<?php if ($next): ?>
			<a class="next <?=$VIA_AJAX?'lightbox':''?>" href="<?=FrontController::GetLink(array('device_id'=>$picture['device_id'], 'id'=>$next))?>">next</a>
		<?php endif; ?>
			<div class="counter">
				<phrase id="PICTURE"/>
				<?=$position+1?> / <?=$total?>
			</div>
			<div class="images">
				<a href="<?=DeviceHelper::GetImage($picture['device_id'], 'xl', $picture['id'])?>" class="external">
					<img src="<?=DeviceHelper::GetImage($picture['device_id'], 'avatar', $picture['id'])?>" alt=""/>
					<img src="<?=DeviceHelper::GetImage($picture['device_id'], 'small', $picture['id'])?>" alt=""/>
					<img src="<?=DeviceHelper::GetImage($picture['device_id'], 'medium', $picture['id'])?>" alt=""/>
				</a>
			</div>
			"<?=$picture['original_filename']?>"<br />
			<?=dateformat($picture['timestamp'])?><br />
			<youser id="<?=$picture['youser_id']?>" type="avatar" force="true" /><br />
			<youser id="<?=$picture['youser_id']?>"/><br />
		</fieldset>

		<fieldset>
			<legend><phrase id="EDIT_IMAGE"/></legend>

			<div class="type-text">
				<label for="master_image"><phrase id="MASTER_IMAGE"/></label>
				<input type="checkbox" name="master_image" id="master_image" value="1" <?=$picture['master_image']=='yes'?'checked="checked"':''?>/>
			</div>

			<div class="type-select">
				<label for="picture_type"><phrase id="PICTURE_TYPE"/></label>
				<select name="picture_type" id="picture_type">
					<option value="-1">- <phrase id="NONE"/> -</option>
				<?php foreach ($types['device_pictures_type'] as $type): ?>
					<option <?=$picture['type']==$type?'selected="selected"':''?>><?=$type?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<div class="type-select">
				<label for="angle"><phrase id="ANGLE"/></label>
				<select name="angle" id="angle">
					<option value="-1">- <phrase id="NONE"/> -</option>
				<?php foreach ($types['angle'] as $angle): ?>
					<option <?=$picture['angle']==$angle?'selected="selected"':''?>><?=$angle?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<div class="type-select">
				<label for="situation"><phrase id="SITUATION"/></label>
				<select name="situation" id="situation">
					<option value="-1">- <phrase id="NONE"/> -</option>
				<?php foreach ($types['situation'] as $situation): ?>
					<option <?=$picture['situation']==$situation?'selected="selected"':''?>><?=$situation?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<input type="hidden" name="device_id" value="<?=$picture['device_id']?>"/>
			<input type="hidden" name="id" value="<?=$picture['id']?>"/>

		</fieldset>

		<div id="type-button">
			<button type="submit"> <phrase id="SAVE"/> </button>
			<input type="checkbox" name="delete" value="absolutely" class="confirm" title_phrase="DELETE_PICTURE" />
			<phrase id="DELETE" />

		</div>
	</form>
</div>