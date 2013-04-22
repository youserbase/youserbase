<select id="parent_selector" class="actions" style="display: none;">
	<option value="-1"><phrase id="NO_PARENT_ITEM"/></option>
<?php foreach ($sites as $module=>$controllers): ?>
	<option class="module" disabled="disabled"><?=$module?></option>
	<?php foreach ($controllers as $controller=>$methods): ?>
		<option class="controller" disabled="disabled"><?=$controller?></option>
		<?php foreach ($methods as $m_index=>$method): ?>
			<option class="method" value="<?=$m_index?>"><?=$method?></option>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endforeach; ?>
</select>