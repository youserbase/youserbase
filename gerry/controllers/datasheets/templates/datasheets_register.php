<div class="rbox">
<h3><phrase id="REGISTER_AS_YOUSER"/></h3>
<div class="register_text" style="margin:7px;">
	<phrase id="BECOME_A_PART_OF_YOUSERBASE"/>
</div>
<div class="register_box" style="float:left;">
	<form  action="<?=FrontController::GetLink('User', 'Access', 'Register')?>" method="post" class="yform columnar lightbox">
		<fieldset>
			<legend><phrase id="REGISTER"/></legend>

		    <div class="type-text">
		        <label for="nickname">
		            <phrase id="NICKNAME"/>:
		        </label><input type="text" class="mandatory" id="nickname" name="nickname" value="<?=!empty($_POST['nickname'])?$_POST['nickname']:''?>"/>
		    </div>
		    <div class="type-text">
		        <label for="email">
		            <phrase id="EMAIL"/>:
		        </label><input type="text" class="mandatory" id="email" name="email" value="<?=!empty($_POST['email'])?$_POST['email']:''?>"/>
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
	        <button>
	            <span><phrase id="REGISTER"/></span>
	        </button>
	    </div>
	</form>
</div>
<div class="datasheet_login" style="float:right;">
	<form action="<?=FrontController::GetLink('User', 'Access', 'Login')?>" method="post" class="validate yform columnar">
		<fieldset>
			<legend> <phrase id="LOGIN"/> </legend>

			<div class="type-text">
				<label for="nickname"> <phrase id="NICKNAME"/> </label>
				<input type="text" class="required" id="nickname" name="nickname" value="<?=empty($_POST['nickname'])?'':$_POST['nickname']?>"/>
			</div>
			<div class="type-text">
				<label for="password"> <phrase id="PASSWORD"/> </label>
				<input type="password" class="required" id="password" name="password"/>
			</div>
			<div class="type-text">
				<label for="store_login"> <phrase id="STORE_LOGIN"/> </label>
				<input type="checkbox" id="store_login" name="store_login" value="1" <?=empty($_POST['store_login'])?'':'checked="checked"'?>/>
			</div>
			<div class="type-check choice">
				<input type="radio" id="1_DAY" name="login_duration" value="<?=24*60*60?>" <?=(!empty($_POST['login_duration']) and $_POST['login_duration']==24*60*60)?'checked="checked"':''?>/>
				1 <phrase id="DAY"/>
			</div>

			<div class="type-check choice">
				<input type="radio" id="1_WEEK" name="login_duration" value="<?=7*24*60*60?>" <?=(!empty($_POST['login_duration']) and $_POST['login_duration']==7*24*60*60)?'checked="checked"':''?>/>
				1 <phrase id="WEEK"/>
			</div>

			<div class="type-check choice">
				<input type="radio" id="1_MONTH" name="login_duration" value="<?=4*7*24*60*60?>" <?=(!empty($_POST['login_duration']) and $_POST['login_duration']==4*7*24*60*60)?'checked="checked"':''?>/>
				1 <phrase id="MONTH"/>
			</div>
		</fieldset>
		<input type="hidden" value="<?=$return_to?>" name="return_to"/>
		<div class="type-button">
			<label>&nbsp;</label>
			<button type="submit" id="login_button"> <span> <phrase id="LOGIN"/> </span> </button>
			<a class="smallhint" href="<?=FrontController::GetLink('ResendActivation')?>">
				<phrase id="RESEND_ACTIVATION_LINK"/>
			</a>
			<a class="smallhint" href="<?=FrontController::GetLink('PasswordReminder')?>">
				<phrase id="LOGIN_PASSWORD_REMINDER"/>
			</a>
		</div>
	</form>

	<script type="text/javascript"/>
	//<![CDATA[
	$(function() {
	<?php if (empty($_POST['store_login'])): ?>
		$('div.choice').hide();
	<?php endif; ?>
		$('#store_login').click(function() {
			if (this.checked)
			{
				$('div.choice').show();
				$(this).next('div').find('input[name="login_duration"]:first').attr('checked', true).focus();
			}
			else
			{
				$('div.choice').hide()
			}
		});

		if (!$('#nickname').val()) {
			$('#nickname').focus();
		} else if (!$('#password').val()) {
			$('#password').focus();
		} else {
			$('#login_button').focus();
		}
	});
	//]]>
	</script>
</div>
</div>