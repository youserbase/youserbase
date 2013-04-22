<?=$this->render_partial('select', array('sites'=>$sites))?>
<form action="<?=FrontController::GetLink()?>" method="post" class="ajax target:tab">
<table style="width: 100%;" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
			<th><phrase id="SITE_LOCATION"/></th>
			<th><phrase id="SITE_TITLE"/></th>
			<th><phrase id="SITE_PARENTSIZE"/></th>
		</tr>
	</thead>
<?php foreach ($sites as $module=>$classes): ?>
	<tbody>
		<tr class="module">
			<td colspan="3"><?=$module?></td>
		</tr>
	<?php foreach ($classes as $class=>$methods): ?>
		<tr class="controller">
			<td colspan="3"><?=$class?></td>
		</tr>
	<?php foreach ($methods as $index=>$method): ?>
		<tr class="method">
			<td><?=$method?></td>
			<td><phrase id="PAGETITLE_<?=$index?>"/></td>
			<td><?=$this->render_partial('parent_site', compact('sites', 'parent_sites', 'index'))?></td>
		</tr>
	<?php endforeach; ?>
	<?php endforeach; ?>
	</tbody>
<?php endforeach; ?>
	<tfoot>
		<tr>
			<td colspan="3">
				<input type="submit" value_phrase="SAVE"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
