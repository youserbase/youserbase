<form action="<?=FrontController::GetLink()?>" method="post" <?=$VIA_AJAX?'class="ajax"':''?>>
<fieldset>
	<legend><phrase id="REMOVE_AS_FRIEND" nickname="<?=$nickname?>"/></legend>

	<p>
		<phrase id="REMOVE_AS_FRIEND_CONFIRM" nickname="<?=$nickname?>">Möchten Sie die Freundschaft mit <?=$nickname?> wirklich beenden?</phrase>
	</p>

	<button type="submit"><span> <phrase id="OK"/> </span></button>
	<button  type="submit" name="cancel" class="cancel <?=$VIA_AJAX?'lightbox close':''?>"><span> <phrase id="CANCEL"/> </span></button>

	<input type="hidden" name="youser_id" value="<?=$youser_id?>"/>
</fieldset>
</form>