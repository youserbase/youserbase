<form action="<?=FrontController::GetLink()?>" method="POST" class="yform columnar">
	<fieldset>
		<div class="type-text">
			<label for="needle"><phrase id="PHRASE"/></label>
			<input type="text" name="needle" id="needle" class="required" value="<?=isset($_POST['needle'])?$_POST['needle']:''?>"/>
		</div>
		<div class="type-button">
			<button> <span><phrase id="SEARCH"/></span> </button>
		</div>
	</fieldset>
<?php if (!empty($result)): ?>
	<fieldset>
	<?php foreach ($languages as $language): ?>
		<div class="type-text">
			<label><?=$language?></label>
			<input type="text" disabled="disabled" value="<?=isset($result[$language])?$result[$language]:''?>"/>
		</div>
	<?php endforeach; ?>
	</fieldset>
<?php endif; ?>
</form>
