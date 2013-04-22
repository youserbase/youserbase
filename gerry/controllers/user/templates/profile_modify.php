<div class="rbox">
	<h1><phrase id="PROFILE_MODIFY" /></h1>
	<form action="<?=FrontController::GetLink()?>" method="post" class="validate yform columnar">
	<?php foreach ($layout as $category=>$items): ?>
		<fieldset>
			<legend>
				<phrase id="PROFILE_<?=strtoupper($category)?>"/>
			</legend>
		<?php foreach ($items as $name=>$data): ?>
			<?php if (isset($data[0]) and $data[0]=='selection'): ?>
			<div class="type-select" name="<?=$category?>">
				<label for="<?=$category?>_<?=$name?>">
					<phrase id="<?=strtoupper($name)?>"/>
				</label>
				<select id="<?=$category?>_<?=$name?>" name="profile[<?=$category?>][<?=$name?>][value]">
					<option value=""><phrase id="PLEASE_CHOOSE" quiet="true"/></option>
				<?php foreach (explode(',', $data[1]) as $item): ?>
					<option value="<?=$item?>" <?=$item==$attributes[$category][$name]['value']?'selected="selected"':''?>>
						<phrase id="<?=strtoupper($item)?>" quiet="true"/>
					</option>
				<?php endforeach; ?>
				</select>
			</div>
			<?php elseif (isset($data[0]) and in_array($data[0], array('string', 'date'))): ?>
			<div class="type-text" name="<?=$category?>">
				<label for="<?=$category?>_<?=$name?>">
					<phrase id="<?=strtoupper($name)?>"/>
				</label><input type="text" id="<?=$category?>_<?=$name?>" name="profile[<?=$category?>][<?=$name?>][value]" value="<?=$attributes[$category][$name]['value']?>" <?=$data[0]=='date'?' class="add_datepicker"':''?>/>
			</div>
			<?php else: ?>
			<?=implode('-:-', $data)?>
			<?php endif; ?>
		<?php endforeach; ?>
		</fieldset>
	<?php endforeach; ?>
		<div class="type-button" name="<?=$category?>">
			<label>&nbsp;</label>
			<button type="submit">
				<span><phrase id="CHANGE" /></span>
			</button>
		</div>
	</form>

	<form action="<?=FrontController::GetLink('Picture')?>" method="post" enctype="multipart/form-data" class="validate yform columnar">
		<fieldset id="pro_mod_picture_upload">
			<legend>
				<phrase id="PROFILE_USER_IMAGE"/>
			</legend>
			<img src="<?=Youser_Image::GetLink($youser->id, 'large')?>" alt_phrase="YOUSER_IMAGE"/>
			<br/>

			<div class="type-text">
				<label for="youser_picture">
					<phrase id="NEW_PICTURE"/>
				</label>
				<input type="file" id="youser_picture" name="youser_picture"/>
			</div>

			<div class="type-check">
				<label for="delete_picture">
					<phrase id="DELETEPICTURE"/>
				</label>
				<input class="confirm" type="checkbox" id="delete_picture" name="delete_picture" value="1" title_phrase="DELETE_YOUSER_PICTURE"/>
			</div>
		</fieldset>

		<div class="type-button" name="<?=$category?>">
			<label>&nbsp;</label>
			<button type="submit">
				<span><phrase id="CHANGE" /></span>
			</button>
		</div>
	</form>
</div>