<div id="feedback_beta">
	<form action="<?=FrontController::GetLink('Submit')?>" method="post" class="ajax">
		<fieldset>
			<h4> <phrase id="SEND_FEED_TO_YB"/> </h4>

			<textarea name="feedback" rows="15" cols="40"></textarea>
			<button type="submit" class="cancel"><span><phrase id="CANCEL"/></span></button>
			<button type="submit"><span> <phrase id="SEND_FEED_TO_YB"/> </span></button>

			<input type="hidden" name="location" value="<?=FrontController::GetOriginalLink()?>"/>
		</fieldset>
	</form>
	<img src="<?=Assets::Image('_t/feedback_beta.gif')?>" alt="" />
</div>
