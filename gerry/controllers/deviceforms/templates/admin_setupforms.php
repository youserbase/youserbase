<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=setupForms">
<dl>
	<dt/>
	<dd>
		<select name='type'>
			<?foreach ($types as $type => $name):?>
				<option value='<?=$type?>' <?if($selected == $type):?> selected='selected' <?endif;?>><?=$name?></option>
			<?endforeach; ?>
		</select>
	</dd>
	<dt/>
	<dd>
		<input type=submit name='Anzeigen' value='Anzeigen'/>
	</dd>
</dl>
</form>


<?if (!empty($tables)):?>
<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=setupForms">
<dl>
	<input type='hidden' name='type' value='<?=$selected ?>'/>
		<dt/>
			<dd>
				<select name='form'>
					<?foreach ($tables as $table => $values):?>
						<option value='<?=$table?>' <?if($selectedForm == $table):?> selected='selected' 		<?endif;?>><?=$table?></option>
					<?endforeach; ?>
				</select>
			</dd>
		<dt/>
	<dd>
		<input type=submit name='Anzeigen' value='Anzeigen'/>
	</dd>
</dl>
</form>
<?endif; ?>


<?if(!empty($showTable)): ?>
	<?foreach ($showTable as $type => $tables): ?>
	<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=safe">
		<dl>
		<dt><legend for="<?=$type ?>"><phrase id="<?=$type ?>"/></legend></dt>
		<dd/>
			<input type='hidden' name='table' value='<?=$type ?>'/>
			<input type='hidden' name='type' value='<?=$selected ?>'/>
			<?foreach ($tables as $value):?>
				<?foreach ($value as $key => $line): ?>
					<dt>
						<label for="<?=$key?>"><phrase id="<?=$key ?>"/></label>
					</dt>
					<dd> 
						<?=$line ?>
					</dd>
				<?endforeach; ?>
			<?endforeach; ?>
			<dt/>
			<dd>
				<input type=submit name='Absenden' value='Absenden'/>
			</dd>
		</dl>
	</form>
	<?endforeach; ?>
<?endif; ?>