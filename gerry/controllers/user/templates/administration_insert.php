<form action="<?=FrontController::GetLink('Insert')?>" method="post">
<dl>
	<dt>
		<label for="nickname"><phrase id="NICKNAME"/>:</label>
	</dt>
	<dd>
		<input type="text" id="nickname" name="nickname"/>
	</dd>
	<dt>
		<label for="email"><phrase id="EMAIL"/>:</label>
	</dt>
	<dd>
		<input type="text" id="email" name="email"/>
	</dd>
	<dt>
		<label for="password"><phrase id="PASSWORD"/>:</label>
	</dt>
	<dd>
		<input type="password" id="password" name="password"/>
	</dd>
	<dt>
		<label for="role"><phrase id="ROLE"/>:</label>
	</dt>
	<dd>
		<select id="role" name="role">
		<?php foreach ($roles as $role): ?>
			<option><?=$role?></option>
		<?php endforeach; ?>
		</select>
	</dd>
	<dt>&nbsp;</dt>
	<dd>
		<input type="submit" value_phrase="ADD"/>
	</dd>
</dl>
</form>
