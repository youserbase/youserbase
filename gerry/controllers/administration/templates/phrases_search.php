<form action="<?=FrontController::GetLink()?>" method="post" class="yform columnar validate ajax target:tab">
	<fieldset>
		<div class="type-text">
			<label for="needle"><phrase id="PHRASE"/></label>
			<input type="text" id="needle" name="needle" class="required" value="<?=$needle?>"/>
		</div>
		<div class="type-text">
			<label for="phrase_id"><phrase id="PHRASE_ID"/></label>
			<input type="text" id="phrase_id" style="width: 300px;" value="<?=$phrase_id?>" readonly="readonly"/>
		</div>
		<div class="type-button">
			<button type="submit"> <span> <phrase id="SEARCH"/> </span> </button>
		</div>
	</fieldset>
</form>