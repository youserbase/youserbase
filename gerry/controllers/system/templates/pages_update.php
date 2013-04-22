<form action="<?=FrontController::GetLink()?>" method="post" class="yform">
	<fieldset>
		<legend><phrase id="EDIT_PAGE_TITLE" page="<?=$page?>"/>
		<div class="type-text">
			<textarea name="content" style="width: 98%; height: 400px;" col="30" rows="20"><?=$data['contents']?></textarea>
		</div>
	</fieldset>
	<div class="type-button">
		<button type="submit"><span><phrase id="SAVE"/></span></button>
		<button type="reset" class="cancel"><span><phrase id="CANCEL"/></span></button>

		<input type="hidden" name="page" value="<?=$page?>"/>
	</div>
</form>