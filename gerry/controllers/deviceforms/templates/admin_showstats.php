<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=manufacturer">
	<dl>
		<dt>
			<input type='hidden' name='type' value='manufacturers'/>
		</dt>
		<dt>
			<label for='manufacturer'><phrase id="MANUFACTURER"/></label>
		</dt>
		<?if (isset($manufacturers)): ?>
		<dd>
			<select name='manufacturers'>
			<?foreach ($manufacturers as $key => $component): ?>
				<option value='<?=$key ?>'>
					<?=$component ?>
				</option>
			<?endforeach; ?>
			</select>
		</dd>
		<dt/>
		<dd>
			<input type=submit name='Neuen erstellen' value='Bearbeiten'/>
		</dd>
		<?else: ?>
		<dd>
			<label>Bisher keine Hersteller vorhanden</label>
		</dd>
		<?endif; ?>
	</dl>
</form>


<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=alterComponents">
	<dl>
		<dt><input type='hidden' name='type' value='components'/>
		</dt>
		<dt><label for='components'><phrase id="components"/></label>
		<?if (isset($components['component_id']) && isset($components['components_name'])): ?>
		<dd>
			<select name='components'>
			<?foreach ($components['component_id'] as $key => $component): ?>
				<option value='<?=$component ?>'>
					<?=$components['components_name'][$key] ?>
				</option>
			<?endforeach; ?>
			</select>
		</dd>
		<dt/>
		<dd>
			<input type=submit name='Bearbeiten' value='Bearbeiten'/>
		</dd>
		<?else: ?>
		<dd>
			<label>Bisher keine Komponenten vorhanden</label>
		</dd>
		<?endif; ?>
	</dl>
</form>


<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=alterComponents">
	<dl>	
		<dt><label for='components'>Funktionen</label>
		<?if (isset($components['function_id']) && isset($components['function_name'])): ?>
		<dd>
			<select name='components'>
				<?foreach ($components['function_id'] as $key => $component): ?>
					<option value='<?=$component ?>'>
						<?=$components['function_name'][$key] ?>
					</option>
				<?endforeach; ?>
			</select>
		</dd>
		<dt/>
		<dd>
			<input type=submit name='Bearbeiten' value='Bearbeiten'/>
		</dd>
		<?else: ?>
		<dd>
			<label>Bisher keine Funktionen vorhanden</label>
		</dd>
		<?endif; ?>
	</dl>
</form>


<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=alterComponents">
	<dl>
		<dt><label for='equipment'>Zubeh�r</label>
		<?if (isset($equipment['equipment_id']) && isset($equipment['equipment_name'])): ?>
		<dd>
			<select name='equipment'>
			<?foreach ($equipment['equipment_id'] as $key => $component): ?>
				<option value='<?=$equipment ?>'>
					<?=$equipment['equipment_name'][$key] ?>
				</option>
			<?endforeach; ?>
			</select>
		</dd>
		<dt/>
		<dd>
			<input type=submit name='Bearbeiten' value='Bearbeiten'/>
		</dd>
		<?else: ?>
		<dd>
			<label>Bisher kein Equipment vorhanden</label>
		</dd>
		<?endif ?>
	</dl>
</form>
<a href='/willms/gerry/index.php?module=deviceForms&controller=admin&method=manufacturer'>L�nder und Sprachen setzen</a>