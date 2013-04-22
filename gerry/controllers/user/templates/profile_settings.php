<form action="<?=FrontController::GetLink()?>" method="post" class="yform columnar validate">
	<fieldset>
		<legend><phrase id="CHANGE_USER_SETTINGS" /></legend>
		<div class="type-text">
			<label for="nickname">
				<phrase id="NICKNAME"/>:
			</label><input type="text" id="nickname" name="nickname" disabled="disabled" value="<?=$youser->nickname?>"/>
		</div>
		<div class="type-text">
			<label for="email">
				<phrase id="EMAIL"/>:
			</label><input type="text" class="required email" id="email" name="email" value="<?=$youser->email?>"/>
		</div>
		<div class="type-select">
			<label for="language">
				<phrase id="LANGUAGE"/>:
			</label>
			<select id="language" name="language">
			<?php foreach ($youser_languages as $language): ?>
				<option value="<?=$language?>" <?=$language==$youser->language?'selected="selected"':''?>><phrase id="LANGUAGE_<?=$language?>" quiet="true"/></option>
			<?php endforeach; ?>
			</select>
		</div>
		<div class="type-text">
			<label for="visible">
				<phrase id="VISIBLE"/>
			</label><input type="checkbox" id="visible" name="visible" value="1" <?=$youser->visible?'checked="checked"':''?>/>
		</div>
	</fieldset>
	<div class="type-button">
		<button>
			<span><phrase id="CHANGE" /></span>
		</button>
	</div>
</form>

<form action="<?=FrontController::GetLink('PasswordChange')?>" method="post" class="yform columnar">
	<fieldset>
		<legend><phrase id="CHANGE_PASSWORD"/></legend>
		<div class="type-text">
			<label for="old_password">
				<phrase id="OLDPASSWORD"/>:
			</label><input type="password" class="required" id="old_password" name="old_password" value=""/>
		</div>
		<div class="type-text">
			<label for="new_password">
				<phrase id="NEWPASSWORD"/>:
			</label><input type="password" id="new_password" name="new_password" value=""/>
		</div>
		<div class="type-text">
			<label for="new_password_confirm">
				<phrase id="CONFIRMNEWPASSWORD"/>:
			</label><input type="password" id="new_password_confirm" name="new_password_confirm" value=""/>
		</div>
	</fieldset>
	<div class="type-button">
		<button>
			<span><phrase id="CHANGE" /></span>
		</button>
	</div>
</form>