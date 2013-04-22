<?if(isset($main_types) && !isset($tables)): ?>
	<?if (!empty($main_types)): ?>
		<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=newDevice&method=createNewDevice'>
			<dl>
				<dt/>
				<dd>
					<input type='hidden' name='createDevice' value='choosetype'/>
				</dd>
				<dt>
					<label for="choose_main_device_type"><phrase id="choose_main_device_type"/></label>
				</dt>
				<dd>
					<select name='main_type'>
						<?foreach ($main_types as $id => $device_type): ?>
							<option value='<?=$device_type?>' <?if (isset($main_type) && $main_type  == $device_type): ?>selected='selected'<?endif;?>><?=$device_type ?></option>
						<?endforeach;?>
					</select>
				</dd>
				<dt/>
				<dd>
					<?if(!isset($device_types)):?>
						<input type='submit' value='Anlegen'/>
					<?endif; ?>
				</dd>
			</dl>
		</form>
	<?else: ?>
		<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=designDevices'>
			<dl>
				<dt>
					<label for="NOCOMPONENTS"><phrase id="NOCOMPONENTS"/></label>
				</dt>
				<dd>
					<input type='submit' value='Neuen Gerätetyp anlegen'/>
				</dd>
			</dl>
		</form>							
	<?endif; ?>
<?endif; ?>
<?if (isset($device_types)): ?>
	<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=newDevice&method=createNewDevice'>
		<dl>
			<dt/>
			<dd>
				<input type='hidden' name='createDevice' value='typechoosen'/>
				<input type='hidden' name='main_type' value='<?=$main_type ?>'/>
			</dd>
			<dt>
				<label for="choose_further_device_type"><phrase id="choose_further_device_type"/></label>
			</dt>
			<dd>
				<select name='device_types[]' multiple='multiple'>
				
					<?foreach ($device_types as $id => $device_type): ?>
						<?if ($device_type != $main_type): ?>
							<option value=<?=$device_type?>'><?=$device_type ?></option>
						<?endif; ?>
					<?endforeach;?>
				</select>
			</dd>
			<dt/>
			<dd>
				<input type='submit' value='Anlegen'/>
			</dd>
		</dl>
	</form>
<?endif; ?>
<?if (isset($tables)): ?>
	<?if (!empty($tables)): ?>
	<form>
	<dl>
		<?foreach ($tables as $key => $table):?>
		<dt>
			<label for='<?=$key ?>'><phrase id="<?=$key ?>"/></label>
		</dt>
		<dd/>
			<?foreach ($table as $row => $key):?>
			<?if (strpos($row, '_id') === false): ?>
			<dt>
				<label for='<?=$row?>'><phrase id="<?=$row ?>"/></label>
			</dt>
			<dd>
				<input type='text' name='<?$row ?>'/>
			</dd>
			<?endif; ?>
			<?endforeach; ?>
		<?endforeach; ?>
	</dl>
	</form>
	<?endif; ?>
<?endif; ?>