<form id="nickpage_add_entry" action="<?=FrontController::GetLink()?>" method="post" class="small validate<?=$VIA_AJAX?' ajax':''?>">
<fieldset>
	<dl>
		<dt>
			<phrase id="MESSAGE"/>:
		</dt>
		<dd>
			<textarea name="message" class="required" style="height: 150px; width: 300px;"></textarea>
		</dd>
		<dt>&nbsp;</dt>
		<dd>
			<button type="submit"> <span> <phrase id="SEND"/> </span> </button>
			<button class="cancel lightbox close">  <span> <phrase id="CANCEL"/> </span> </button>
		</dd>
	</dl>
</fieldset>
<input type="hidden" name="youser_id" value="<?=$youser_id?>"/>
</form>