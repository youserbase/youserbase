<form class="onchange_submit" action="<?=FrontController::GetLink()?>" method="post" style="float: right;">
	<select name="language">
	<?php foreach ($phrase_languages as $language): ?>
		<option <?=$language==$current_language?'selected="selected"':''?>><?=$language?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" value_phrase="CHANGE"/>
</form>
