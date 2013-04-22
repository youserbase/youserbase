<form action="<?=FrontController::GetLink('Insert')?>" method="post">
<dl>
	<dt>
		<label for="navigation_id"><phrase id="NAVIGATION"/>:</label>
	</dt>
	<dd>
		<select id="navigation_id" name="navigation_id" class="select_or_input">
		<?php foreach ($navigation_ids as $navigation_id): ?>
			<option <?=($navigation_id==$current_navigation_id)?'selected="selected"':''?>><?=$navigation_id?></option>
		<?php endforeach; ?>
			<option value="-1"><phrase id="NEWNAVIGATION"/> &raquo;</option>
		</select>
		<input type="text" name="new_navigation"/>
	</dd>
	<dt>
		<label for="section_id"><phrase id="SECTION"/>:</label>
	</dt>
	<dd>
		<select id="section_id" name="section_id" class="select_or_input">
		<?php foreach ($current_navigation as $section): ?>
			<option value="<?=$section['name']?>"><phrase id="<?=$section['name']?>"/>
		<?php endforeach; ?>
			<option value="-1"><phrase id="NEWSECTION"/> &raquo;</option>
		</select>
		<input type="text" name="new_section_id"/>
	</dd>
	<dt>
		<label for="title"><phrase id="TITLE"/>:</label>
	</dt>
	<dd>
		<input type="text" id="title" name="title"/>
	</dd>
	<dt>
		<label for="link"><phrase id="LINK"/>:</label>
	</dt>
	<dd>
		<select name="link" id="link" class="select_or_input">
			<optgroup label_phrase="ACTIONS"/>
			<?php foreach ($actions as $action): ?>
				<option><?=$action?></option>
			<?php endforeach; ?>
			</optgroup>
		<?php if (!empty($plugins)): ?>
			<optgroup label_phrase="PLUGINS">
			<?php foreach ($plugins as $plugin): ?>
				<option><?=$plugin?></option>
			<?php endforeach; ?>
			</optgroup>
		<?php endif; ?>
			<optgroup label_phrase="OTHER">
				<option value="-1"><phrase id="EXTERNALLINK"/> &raquo;</option>
				<option value="-2"><phrase id="SYSTEM_PAGE"/></option>
			</optgroup>
		</select>
		<input type="text" name="external_link"/>
	</dd>
	<dt>
		<label><phrase id="VISIBILITY"/>:</label>
	</dt>
	<dd>
	<?php foreach ($roles as $role): ?>
		<input type="checkbox" name="roles[]" value="<?=$role?>"/>
		<img src="<?=Helper::GetIconForRole($role)?>" alt=""/>
		<?=$role?>
		<br/>
	<?php endforeach; ?>
	</dd>
	<dt>&nbsp;</dt>
	<dd>
		<input type="submit" value_phrase="ADD"/>
	</dd>
</dl>
</form>