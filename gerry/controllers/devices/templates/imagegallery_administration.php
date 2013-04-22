<div class="rbox" id="images_administration">
<?php /*
	<form id="upload_form" action="" method="post" class="yform columnar">
		<fieldset>
			<legend><phrase id="UPLOAD_IMAGE" /></legend>

			<div class="type-text">
				<label for="upload_pictures">
					<a class="add" href="#" style="display: none;"><phrase id="MORE"/> &hellip;</a>
					<phrase id="UPLOAD_PICTURE"/>
				</label>

				<input id="upload_pictures" type="file" class="required" name="picture_source_path[]"/>
			</div>

			<div class="type-select">
				<label for="picture_type"><phrase id="PICTURE_TYPE"/></label>
				<select name="picture_type">
					<option value="official"><phrase id="MANUFACTURER_PICTURE" quiet="true"/></option>
					<option value="user"><phrase id="YOUSER_PICTURE" quiet="true"/></option>
					<option value="accessory">accessory</option>
					<option value="screenshots">screenshot</option>
					<option value="showcase">showcase</option>
				</select>
			</div>
		</fieldset>
		<div class="type-button">
			<button type="submit"><span>Upload</span></button>
		</div>
	</form>
*/ ?>

	<dl class="picture_holder stored:device_id:<?=$device_id?>">
	<?php foreach ($types as $category): ?>
		<?php if (!isset($pictures[$category])) continue; ?>
		<dt><?=$category?></dt>
		<dd>
			<ul class="sorter stored:type:<?=$category?>">
			<?php foreach ($pictures[$category] as $index => $picture): ?>
				<li class="position_<?=$index?> stored:picture_id:<?=$picture['id']?>">
					<a href="<?=FrontController::GetLink('Edit', array('device_id'=>$device_id, 'id'=>$picture['id']))?>" class="lightbox">
						<img src="<?=DeviceHelper::GetImage($device_id, 'avatar', $picture['id'])?>" alt="" />
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</dd>
	<?php endforeach; ?>
	</dl>

	<br style="clear: left;" />

	<button class="stored:uri:<?=FrontController::GetLink('Reorder')?>"> <phrase id="SAVE"/> </button>
	<button class="cancel"> <phrase id="RESET"/> </button>
</div>