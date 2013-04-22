<form action="<?=FrontController::GetLink('Login')?>" method="post" id="register_form" class="validate yform" style="width: 47%; float: left;">
	<fieldset style="height: 230px;">

		<legend> <phrase id="LOGIN"/> </legend>

		<div class="type-text">
			<label for="nickname"> <phrase id="NICKNAME"/> </label>
			<input type="text" class="required" id="nickname" name="nickname" value="<?=empty($_POST['nickname'])?'':$_POST['nickname']?>"/>
		</div>
		<div class="type-text">
			<label for="password"> <phrase id="PASSWORD"/> </label>
			<input type="password" class="required" id="password" name="password"/>
		</div>
		<div class="type-check">
			<input type="checkbox" id="store_login" name="store_login" value="1" <?=empty($_POST['store_login'])?'':'checked="checked"'?>/>
			<label for="store_login"> <phrase id="STORE_LOGIN"/> </label>
		</div>
<?php
/*
		<div class="white">
			<fb:login-button size="large" background="white" length="long" onclick="Shadowbox.close();" onlogin="auth_using_fb();"></fb:login-button>
		</div>
*/
?>

	</fieldset>
	<div class="type-button">
		<label>&nbsp;</label>
		<button type="submit" id="login_button"> <span> <phrase id="LOGIN"/> </span> </button>
	</div>
	<div>
		<a class="smallhint" href="<?=FrontController::GetLink('PasswordReminder')?>"><phrase id="LOGIN_PASSWORD_REMINDER"/></a>
	</div>
</form>

<form action="<?=FrontController::GetLink('Register')?>" method="post" class="yform" id="register_form" style="width: 47%; float: right;">
	<fieldset style="height: 230px;">
		<legend> <phrase id="REGISTER" /> </legend>
		<div class="register_text">
			<phrase id="BECOME_A_PART_OF_YOUSERBASE"/>
		</div>
		<div class="type-text">
			<label for="nickname2">
				<phrase id="REGISTER_NICKNAME"/>:
			</label><input type="text" class="mandatory required" id="nickname2" name="nickname" value="<?=!empty($_POST['nickname'])?$_POST['nickname']:''?>"/>
		</div>
		<div class="type-text">
			<label for="email">
				<phrase id="EMAIL"/>:
			</label><input type="text" class="mandatory required email" id="email" name="email" value="<?=!empty($_POST['email'])?$_POST['email']:''?>"/>
		</div>
		<div class="type-text">
			<label for="password">
				<phrase id="PASSWORD"/>:
			</label><input type="password" class="mandatory" id="password" name="password"/>
		</div>
		<div class="type-text">
			<label for="password_confirm">
				<phrase id="PASSWORD_CONFIRM"/>:
			</label><input type="password" class="mandatory" id="password_confirm" name="password_confirm"/>
		</div>
	</fieldset>
	<div class="type-button">
		<label>&nbsp;</label>
		<button type="submit"> <span><phrase id="REGISTER"/></span> </button>
	</div>
	<div>
		<a class="smallhint" href="<?=FrontController::GetLink('ResendActivation')?>"><phrase id="RESEND_ACTIVATION_LINK"/></a>
	</div>
</form>

<br style="clear: both;" />

<script type="text/javascript">
//<![CDATA[
$('#register_form').validate();
$(function() {
	if (!$('#nickname').val().trim().length) {
		$('#nickname').focus();
	} else if (!$('#password').val().trim().length) {
		$('#password').focus();
	} else {
		$('#login_button').focus();
	}
//	FB.XFBML.Host.parseDomTree();
});
//]]>
</script>