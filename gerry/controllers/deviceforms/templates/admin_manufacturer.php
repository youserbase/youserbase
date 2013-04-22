<?if (isset($manufacturer)): ?>
	<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=manufacturer'>
		<dl>
			<dt/>
			<dd>
				<input type='hidden' name='setManufacturer' value='valuesSet'/>
			</dd>
			<?foreach ($manufacturer as $key => $value): ?>
				<?if (strpos($key, '_id') === false): ?>
					<dt>
						<label for='<?=$key ?>'><phrase id="<?=$key ?>"/></label>
					</dt>
					<dd>
						<input type='text' name='<?=$key ?>'/>
					</dd>
				<?endif; ?>
			<?endforeach; ?>
			<dt>
				<label for='country'><phrase id="country"/></label>
			</dt>
			<dd>
				<select name='country'>
					<?foreach ($countries as $key => $entry): ?>
						
							<option value='<?=$key ?>'><?=$entry?></option>
						
					<?endforeach; ?>
				</select>
			<dt/>
			<dd>
				<input type='submit' value='Erstellen'/>
			</dd>
		</dl>
	</form>
<?endif; ?>

<?if (isset($manufacturers)): ?>
	<?if (!empty($manufacturers)): ?>
	<form method='post' action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=manufacturer'>
	<dl>
		<dt/>
		<dd>
			<input type='hidden' name='setManufacturer' value='show'/>
		</dd>
		<dt>
			<label for='manufacturer'><phrase id="manufacturer"/></label>
		</dt>
		<dd>
			<select name='manufacturer'>
				<?foreach ($manufacturers as $key => $value): ?>
					<option value='<?=$key ?>'><?=$value ?></option>
				<?endforeach; ?>
			</select>
		</dd>	
	</dl>
	</form>
	<?endif; ?>
<?endif; ?>