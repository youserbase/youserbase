<form action="<?=FrontController::GetLink('Manufacturer_Edit')?>" method="post" enctype="multipart/form-data">
<fieldset>
	<dl>
		<dt>
			<label for="manufacturer_name">
				<phrase id="MANUFACTURER_NAME"/>
			</label>
		</dt>
		<dd>
			<input id="manufacturer_name" name="manufacturer_name" type="text" value="<?=$manufacturer['manufacturer_name']?>"/>
		</dd>

		<dt>
			<label for="manufacturer_website">
				<phrase id="MANUFACTURER_WEBSITE"/>
			</label>
		</dt>
		<dd>
			<input id="manufacturer_website" name="manufacturer_website" type="text" value="<?=$manufacturer['manufacturer_website']?>"/>
		</dd>

		<dt>
			<label for="manufacturer_country">
				<phrase id="MANUFACTURER_COUNTRY"/>
			</label>
		</dt>
		<dd>
			<select id="manufacturer_country" name="manufacturer_country">
				<option value="">&nbsp;</option>
			<?php foreach (Helper::GetCountries() as $iso=>$name): ?>
				<option value="<?=$iso?>" <?=($iso==$manufacturer['country_id'])?'selected="selected"':''?> style="background: url(<?=Assets::Image('flags/%s.png', strtolower($iso))?>) left center no-repeat; padding-left: 20px;">
					<?=$iso?> - <?=$name?>
				</option>
			<?php endforeach; ?>
			</select><br/>
		</dd>
		<dt>
			<label for="manufacturer_logo">
				<phrase id="MANUFACTURER_LOGO"/>
			</label>
		</dt>
		<dd>
			<input id="manufacturer_logo" name="manufacturer_logo" type="file" rel="upload_url_toggle"/>
			<br/>
			oder URL: <input type="text" name="manufacturer_logo_url" rel="upload_url_toggle"/>
			<br/>
			<input type="reset" onclick="$(':input[rel=upload_url_toggle]').val('').change(); return false;"/>
		</dd>
	</dl>
	<button type="submit"><span> Ok </span></button>

	<input type="hidden" name="manufacturer_id" value="<?=$manufacturer['manufacturer_id']?>"/>
</fieldset>
</form>

<h2>Bilder:</h2>
<dl>
<?php foreach (glob(ASSETS_IMAGE_DIR.'manufacturers/'.strtolower(preg_replace('/[^[:alnum:]]/', '', str_replace(array('MANU_','_'), '', $manufacturer['manufacturer_name'])).'_logo.*')) as $file): ?>
	<dt>
		<?=preg_replace('/^.*_logo\.(.*)\..*$/', '$1', $file)?>
	</dt>
	<dd>
		<img src="http://assets.youserbase.org/manufacturers/<?=basename($file)?>" alt="<?=$file?>" style="border: 1px solid black; padding: 2px;"/><br/>
		<?=implode(' x ', array_slice(getimagesize($file), 0, 2))?>
	</dd>
<?php endforeach; ?>
</dl>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$(':input[rel=upload_url_toggle]').change(function() {
		$(':input[rel=upload_url_toggle]').removeClass('active').attr('disabled', false);
		if ($(this).val()) {
			$(this).addClass('active');
			$(':input[rel=upload_url_toggle]:not(.active)').attr('disabled', true).val('');
		}
	});
});
//]]>
</script>