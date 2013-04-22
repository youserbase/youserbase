<form action="<?=FrontController::GetLink('Insert')?>" method="post" class="yform columnar">
	<fieldset>
		<legend><phrase id="INSERT_PERMISSION"/></legend>

		<div class="type-select">
			<label for="location"><phrase id="NAVILOCATION"/></label>
			<select name="location" id="location" class="actions">
			<?php foreach (FrontController::GetAvailableActions(false, true) as $module=>$controllers): ?>
				<option class="module"><?=$module?></option>
				<?php foreach ($controllers as $controller=>$methods): ?>
					<option class="controller" value="<?=$module?>/<?=$controller?>"><?=$controller?></option>
					<?php foreach ($methods as $m_index=>$method): ?>
						<option class="method" value="<?=$module?>/<?=$controller?>/<?=$method?>"><?=$method?></option>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</select>
		</div>

		<div class="type-text">
			<label><phrase id="PERMISSIONS"/></label>
			<div style="float: left;">
			<?php foreach ($permissions as $role): ?>
				<input id="role_<?=$role?>" type="checkbox" name="permissions[]" value="<?=$role?>" style="display: inline;"/>
				<img src="<?=Helper::GetIconForRole($role)?>" alt=""/>
				<?=$role?>
				<br/>
			<?php endforeach; ?>
			</div>
		</div>
	</fieldset>

	<div class="type-button">
		<label>&nbsp;</label>
		<button type="submit" class="add"><span><phrase id="ADD"/></span></button>
	</div>
</form>
