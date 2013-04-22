<form action="<?=FrontController::GetLink()?>" method="post" class="yform columnar" enctype="multipart/form-data">
	<fieldset>
		<div class="type-text">
			<label for="phrase"><phrase id="PHRASE"/>:</label>
			<input type="text" id="phrase" name="phrase"/>
		</div>
		<div class="type-text">
			<label for="pcontent"><phrase id="CONTENT"/>:</label>
			<input type="text" id="pcontent" name="content"/>
		</div>
		<div class="type-text">
			<label for="language"><phrase id="LANGUAGE"/>:</label>
			<input type="text" id="language" name="language" value="<?=$current_language?>"/>
		</div>
		<div class="type-text">
			<phrase id="OR"/>
		</div>
		<div class="type-text">
			<label for="phrase_file"><phrase id="FILE"/></label>
			<input type="file" id="phrase_file" name="file"/>
		</div>
		<div class="type-button">
			<button type="submit"> <span><phrase id="ADD"/></span> </button>
		</div>
	</fieldset>
</form>
