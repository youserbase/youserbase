<?if (!isset($tables)): ?>

	<?if (isset($device_types) && !empty($device_types)): ?>
	<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=designDevices'>
	<input type='hidden' name='newDevice' value='describe'/>
	<select name='device_types'>
			<?foreach ($device_types as $id => $type):?>
				<option value='<?=$id ?>'><?=$type?></option>
			<?endforeach; ?>
		</select>
		<input type='submit' value_phrase='SHOWNEWDEVICECLASS'/>
	</form>
	<?endif; ?>
	
	<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=designDevices'>
		<input type='hidden' name='newDevice' value='show'/>
		<input type='submit' value_phrase='CREATENEWDEVICECLASS'/>
	</form> 
<?endif; ?>
 
<?if (isset($components)): ?>
	<select multiple='miultiple'>
		<?foreach ($components as $component): ?>
			<option value="<?=$component ?>"><phrase id="<?=$component ?>"/></option>
		<?endforeach; ?>
	</select>
<?endif; ?>

<?if (isset($tables)): ?>
<div id='drag_components'>
	<dl>
		<dt>
			<label for='components'><phrase id="components"/></label>
		</dt>
		<dd>
			<select name='comp' multiple='multiple'>
				<?foreach ($tables as $table): ?>
					<?if (true): ?>
							<option class='all_components' value="<?=$table ?>"><?=$table ?></option>
					<?endif; ?>
				<?endforeach; ?>
		</select>
		</dd>
	</dl>
</div>

<form id='dropform' method='post' post='/willms/gerry/index.php?module=deviceForms&controller=admin&method=designdevices'>
<dl>
	<dt/>
	<dd>
		<input type='hidden' name='newDevice' value='set'/>
	</dd>
	<dt>
		<label for='device_type_name'><phrase id="device_type_name"/></label>
	</dt>
	<dd>
		<input type='text' name='device_type_name'/>
	</dd>
	<dt>
		<label for='device_type_class'><phrase id="device_type_class"/></label>
	</dt>
	<dd>
		<?if(isset($classes)):?>
			<select name='device_type_class'/>
				<?foreach ($classes as $class):?>
					<option value="<?=$class?>"><phrase id="<?=$class?>"/></option>
				<?endforeach;?>
			</select>
		<?endif;?>
	</dd>
	<dt>
		<label for='components'><phrase id="components"/></label>
	</dt>
	<dd>
		<select name='components[]' class ='components' multiple='multiple'/></select>
	</dd>	
	<dt/>
	<dd>
		<input type='submit' value='Eintragen' name='Eintragen'/>
	</dd>
</dl>
</form>

<?endif; ?>


<script src="http://dev.jquery.com/view/tags/ui/1.5rc1/source/ui.core.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.5rc1/source/ui.draggable.js"></script>
<script src="http://dev.jquery.com/view/tags/ui/1.5rc1/source/ui.droppable.js"></script>