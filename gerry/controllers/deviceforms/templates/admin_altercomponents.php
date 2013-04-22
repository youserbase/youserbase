<?if (isset($content)): ?>
	<form action='/willms/gerry/index.php?module=deviceForms&controller=admin&method=alterComponents&alter=alter' method='post'>
	<dl>
	<dt/>
	<dd><input type='hidden' name='alter' value='alter'/></dd>
	<dt/>
	<dd><input type='hidden' name='table' value='<?=$table ?>'/></dd>
	<?foreach ($content as $key => $value): ?>
		<?if (strpos($key, '_id') === false): ?>
			<dt><label for='<?=$key ?>'><phrase id="<?=$key ?>"/></label></dt>
			<dd><input type='text' name='<?=$key ?>' value='<?=$value ?>'/></dd>
		<?else :?>
			<dt/>
			<dd><input type='hidden' name='<?=$key ?>' value='<?=$value ?>'/></dd>
		<?endif; ?>
	<?endforeach; ?>
		<dt/>
		<dd><input type='submit' value='Ändern'/></dd>
	</dl>
	</form>
<?endif; ?>