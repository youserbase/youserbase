<div class="rbox">
	<h3>
		<phrase id="UPLOAD_PICTURE" />
	</h3>
	<form id="upload_form" action="<?=FrontController::GetLink('AddImage')?>" method="post" class="yform columnar" enctype="multipart/form-data">
		<input id="upload_pictures" type="file" class="required" name="picture_source_path[]"/>
		<div class="more_pictures">
			<a class="add" href="#" style="display: none;"><phrase id="MORE"/> &hellip;</a>
		</div>
		<label for="picture_type"><phrase id="PICTURE_TYPE"/></label>
		<select id="picture_type" name="picture_type">
			<?php if(Youser::Is('root', 'administrator', 'god')):?>
				<option value="official"><phrase id="MANUFACTURER_PICTURE" quiet="true"/></option>
			<?php endif;?>
			<option value="user"><phrase id="YOUSER_PICTURE" quiet="true"/></option>
		</select>
		<input type="hidden" name="device_id" value="<?=$device_id?>" />
		<div class="buttons">
			<button>
				<span>
					<phrase id="UPLOAD" quiet="true"/>
				</span>
			</button>
			<button class="cancel">
				<span>
					<phrase id="CANCEL"/>
				</span>
			</button>
		</div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
jQuery(function($) {
	$('#upload_form a.add').show().click(function () {
		if ($(this).closest('input').siblings('#upload_pictures').length >= 10) {
			return false;
		}
		$(this).closest('form').find('div.more_pictures').before('<div class="new_upload"><input id="upload_pictures" type="file" class="required" name="picture_source_path[]"/> <a class="remove" href="#"><phrase id="REMOVE"/></a></div>');
		return false;
	});
	$('#upload_form a.remove').live('click', function () {
		$(this).closest('.new_upload').remove();

		return false;
	});
});
//]]>
</script>