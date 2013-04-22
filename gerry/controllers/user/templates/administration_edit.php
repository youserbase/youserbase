<form action="<?=FrontController::GetLink()?>" method="post" class="validate ajax">
<dl>
	<dt>
		<label for="nickname"><phrase id="NICKNAME"/>:</label>
	</dt>
	<dd>
		<input type="text" class="required" id="nickname" name="nickname" value="<?=$youser->nickname?>" class="required"/>
	</dd>
	<dt>
		<label for="email"><phrase id="EMAIL"/>:</label>
	</dt>
	<dd>
		<input type="text" class="required" id="email" name="email" value="<?=$youser->email?>" class="required"/>
	</dd>
	<dt>
		<label for="role"><phrase id="ROLE"/>:</label>
	</dt>
	<dd>
		<select name="role" id="role">
		<?php foreach ($roles as $role): ?>
			<option <?=$youser->role==$role?'selected="selected"':''?>><?=$role?></option>
			<?php if ($role==$youser->role) break; ?>
		<?php endforeach; ?>
		</select>
	</dd>
	<dt>
		<label for="roles"><phrase id="ROLES"/>:</label>
	</dt>
	<dd>
		<select name="roles[]" id="roles" multiple="multiple">
		<?php foreach (YouserPermissions::GetRoles() as $role): ?>
			<option <?=in_array($role, YouserPermissions::Get($youser->id)->get_roles())?'selected="selected"':''?>><?=$role?></option>
		<?php endforeach; ?>
		</select>
	</dd>
	<dt>
		<label for="visible"><phrase id="VISIBLE"/>:</label>
	</dt>
	<dd>
		<input type="checkbox" id="visible" name="visible" value="yes" <?=$youser->visible?'checked="checked"':''?>/>
	</dd>
	<dt>
		<label for="blocked"><phrase id="BLOCKED"/>:</label>
	</dt>
	<dd>
		<input type="checkbox" id="blocked" name="blocked" value="yes" <?=($youser->blocked=='yes' or $youser->blocked===true)?'checked="checked"':''?>/>
	</dd>
	<dt>&nbsp;</dt>
	<dd>
		<input type="submit" value_phrae="CHANGE"/>
	</dd>
</dl>
<input type="hidden" name="youser_id" value="<?=$youser->id?>"/>
</form>