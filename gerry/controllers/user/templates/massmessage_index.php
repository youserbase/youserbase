<form method="post" action="<?=FrontController::GetLink('Send')?>">
	<div class="submit">
		<button>
			<span>
				<phrase id="SEND"/>
			</span>
		</button>
	</div>
	<div class="subject">
		<label for="'subject"><phrase id="SUBBJECT"/></label>
		<input type="text" name="subject"/>
	</div>
	<div class="message">
		<label for="message"><phrase id="MESSAGE"/></label>
		<textarea id="message "name="mail_text" cols="60" rows="20"></textarea>
	</div>
	<div class="select_all">
		Alle markieren
	</div>
	<?php foreach ($yousers as $count => $youser):?>
		<div>
			<?=$count+1?>: 
			<input type="checkbox" name="youser_id[]" class="youser_id" value="<?=$youser['youser_id']?>"/> 
			<youser id="<?=$youser['youser_id']?>"/>
		</div>
	<?php endforeach;?>
	<div class="select_all">
		Alle markieren
	</div>
	<div class="submit">
		<button>
			<span>
				<phrase id="SEND"/>
			</span>
		</button>
	</div>
</form>

<script>
$('.select_all').livequery('click', function(){
	if($('.youser_id').hasClass('checked'))
	{
		$('.youser_id').removeAttr('checked');
		$('.youser_id').removeClass('checked', '');
	}
	else
	{
		$('.youser_id').attr('checked', 'checked');
		$('.youser_id').addClass('checked');
	}
});
</script>