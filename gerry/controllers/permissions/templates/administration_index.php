<form action="<?=FrontController::GetLink()?>" method="post">
<table style="width: 100%;" cellspacing="0" cellpadding="2" id="permissions">
	<colgroup>
		<col/>
	<?php foreach ($roles as $role): ?>
		<col width="20px"/>
	<?php endforeach; ?>
		<col/>
	</colgroup>
	<thead>
		<tr>
			<th class="blank">&nbsp;</th>
			<th colspan="<?=count($roles)?>"><phrase id="PERMISSIONS"/></th>
			<th class="blank">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr class="header">
			<th><phrase id="NAVILOCATION"/></th>
			<?php foreach ($roles as $role): ?>
			<th class="highlight">
				<img src="<?=Helper::GetIconForRole($role)?>" alt="<?=$role?>" title="<?=$role?>"/>
			</th>
			<?php endforeach; ?>
			<th>&nbsp;</th>
		</tr>
<?php $row_count = 0; foreach ($permissions as $module=>$module_data): ?>
<?php if (isset($module_data['permissions'])): ?>
	<tr class="r<?=$row_count%2?> a<?=$row_count++%2?> highlight row">
		<td><?=$module?></td>
	<?php foreach ($roles as $role): ?>
		<td class="highlight">
			<input type="checkbox" name="permissions[<?=$module?>][]" value="<?=$role?>" <?=in_array($role, $module_data['permissions'])?'checked="checked"':''?>"/>
		</td>
	<?php endforeach; ?>
		<td style="text-align: right;">
			<a href="<?=FrontController::GetLink('Delete', array('location'=>$module))?>" title_phrase="DELETEPERMISSION" class="confirm">
				<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
			</a>
		</td>
	</tr>
<?php else: ?>
	<tr class="r<?=$row_count%2?> a<?=$row_count%2?>">
		<td colspan="<?=2+count($roles)?>"><?=$module?></td>
	</tr>
<?php endif; ?>
<?php foreach ($module_data['controllers'] as $controller=>$controller_data): ?>
<?php if (isset($controller_data['permissions'])): ?>
	<tr class="r<?=$row_count%2?> a<?=$row_count++%2?> highlight row">
		<td class="controller"><?=$controller?></td>
	<?php foreach ($roles as $role): ?>
		<td class="highlight">
			<input type="checkbox" name="permissions[<?=$module?>/<?=$controller?>][]" value="<?=$role?>" <?=in_array($role, $controller_data['permissions'])?'checked="checked"':''?>"/>
		</td>
	<?php endforeach; ?>
		<td style="text-align: right;">
			<a href="<?=FrontController::GetLink('Delete', array('location'=>$module.'/'.$controller))?>" title_phrase="DELETEPERMISSION" class="confirm">
				<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
			</a>
		</td>
	</tr>
<?php else: ?>
	<tr class="r<?=$row_count%2?> a<?=$row_count%2?>">
		<td class="controller" colspan="<?=2+count($roles)?>"><?=$controller?></td>
	</tr>
<?php endif; ?>
<?php foreach ($controller_data['methods'] as $method=>$permission): ?>
	<tr class="r<?=$row_count%2?> a<?=$row_count++%2?> highlight row">
		<td class="method"><?=$method?></td>
	<?php foreach ($roles as $role): ?>
		<td class="highlight">
			<input type="checkbox" name="permissions[<?=$module?>/<?=$controller?>/<?=$method?>][]" value="<?=$role?>" <?=in_array($role, $permission)?'checked="checked"':''?>"/>
		</td>
	<?php endforeach; ?>
		<td style="text-align: right;">
			<a href="<?=FrontController::GetLink('Delete', array('location'=>$module.'/'.$controller.'/'.$method))?>" title_phrase="DELETEPERMISSION" class="confirm">
				<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
			</a>
		</td>
	</tr>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr style="text-align: right;">
			<td colspan="<?=2+count($roles)?>">
				<button type="submit"><span><phrase id="SAVE"/></span></button>
			</td>
		</tr>
	</tfoot>
</table>
</form>