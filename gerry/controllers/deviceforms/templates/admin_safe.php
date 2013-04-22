
<?if(!empty($showTable)): ?>
	<?foreach ($showTable as $type => $tables): ?>
	<form method='post'  action="/willms/gerry/index.php?module=deviceForms&controller=admin&method=safe">
		<fieldset>
			<legend><?=$type ?></legend>
			<?foreach ($tables as $value):?>
				<?foreach ($value as $key => $line): ?>
					<label for='<?=$key?>'><phrase id="<?=$key ?>"/></label>
					<?=$line ?>
					<br/>
				<?endforeach; ?>
			<?endforeach; ?>
		</fieldset>
	</form>
	<?endforeach; ?>
<?endif; ?>
<a href="/willms/gerry/index.php?module=deviceForms&controller=admin&method=setupForms">Weitere Komponenten hinzuf�gen</a>
