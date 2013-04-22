<div class="rbox">
<h3><phrase id="ADMINISTRATION"/></h3>
	<form action="" method="POST">
		<input type="hidden" name='start' value="go"/>
		<button type="submit"><span><phrase id="START_CLASSGENERATOR"/></span></button>
	</form>
	<?php if (isset($status)):?>
		<?=$status?>
	<?php endif;?>

	<form action="<?=FrontController::GetLink('datasheets', 'datasheets', 'deletedevice')?>" method="POST">
		<button type="submit" class="confirm" title="ARE_YOU_SURE?"><span><phrase id="DELETE_DEPRECATED"/></span></button>
	</form>
	<?php if (isset($deleted)):?>
		<?=$deleted?>
	<?php endif;?>
</div>