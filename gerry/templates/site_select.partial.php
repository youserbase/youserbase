<select name="<?=isset($name)?$name:'site'?>" <?=isset($id)?'id="'.$id.'"':''?> class="actions">
<?php if (isset($empty)): ?>
	<option value="-1"><phrase id="<?=$empty?>" quiet="true"/></option>
<?php endif; ?>
<?php foreach (FrontController::GetAvailableActions(false, true) as $module=>$controllers): ?>
	<option class="module" <?=empty($enabled)?'disabled="disabled"':''?>><?=$module?></option>
	<?php foreach ($controllers as $controller=>$methods): ?>
		<option class="controller" <?=empty($enabled)?'disabled="disabled"':''?>><?=$controller?></option>
		<?php foreach ($methods as $m_index=>$method): ?>
			<option class="method" value="<?=$m_index?>" <?=(!empty($selected) and $selected==$m_index)?'selected="selected"':''?>><?=$method?></option>
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endforeach; ?>
</select>