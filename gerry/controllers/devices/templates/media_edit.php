<form action="<?=FrontController::GetLink(compact('device_id', 'media_id'))?>" method="post" class="yform columnar">
	<fieldset>
		<legend><phrase id="EDIT_MEDIA" /></legend>

		<div class="type-text">
			<label for="title"><phrase id="TITLE" /></label>
			<input type="text" name="title" value="<?=$title?>" />
		</div>

		<div class="type-button">
			<button class="save"><phrase id="SAVE" /></button>
		</div>
	</fieldset>
</form>