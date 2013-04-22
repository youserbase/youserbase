<form action="<?=FrontController::GetLink()?>" method="post" class="validate yform columnar">
	<fieldset>
		<legend> <phrase id="RESEND_ACTIVATION_LINK"/> </legend>

		<div class="type-text">
			<label for="email"> <phrase id="EMAIL"/> </label>
			<input type="text" class="required email" id="email" name="email" value="<?=empty($_POST['email'])?'':$_POST['email']?>"/>
		</div>
	</fieldset>
	<div class="type-button">
		<label>&nbsp;</label>
		<button type="submit"> <span> <phrase id="SEND"/> </span> </button>
	</div>
</form>