<form action="<?=FrontController::GetLink()?>" method="post" <?=$VIA_AJAX?'class="ajax"':''?>>
<fieldset>
	<legend><phrase id="ADD_AS_FRIEND" nickname="<?=$nickname?>"/></legend>

	<p>
		<phrase id="ADD_AS_FRIEND_CONFIRM" nickname="<?=$nickname?>">Möchten Sie <?=$nickname?> wirklich als Freund hinzufügen?</phrase>
	</p>

	<input type="submit" name="submit" value_phrase="OK"/>
	<input type="submit" name="cancel" value_phrase="CANCEL" class="cancel <?=$VIA_AJAX?'lightbox close':''?>"/>

	<input type="hidden" name="youser_id" value="<?=$youser_id?>"/>
</fieldset>
</form>