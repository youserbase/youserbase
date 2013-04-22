<form id="plugin_select" action="<?=FrontController::GetLink()?>" method="post" class="yform <?=$VIA_AJAX?'ajax lightbox':''?>">
	<fieldset>
		<legend><phrase id="ADD_PLUGIN" /></legend>
		<div class="type-select">
			<select>
				<option value="none">&ndash;</option>
			<?php foreach ($plugins as $id): ?>
				<option><?=$id?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</fieldset>

	<fieldset>
		<legend><phrase id="PLUGINS" /></legend>
		<ol id="plugin_list" class="plugin-list sortable">
		<?php foreach ($site_plugins as $index=>$name): $parts = explode(':', $name); ?>
			<li>
				<input type="checkbox"/>

				<input type="hidden" name="plugins[]" value="<?=reset($parts)?>"/>

				<label for="plugin_<?=$index?>"><?=reset($parts)?></label>

				<input id="plugin_<?=$index?>" type="text" name="plugin_methods[]" value="<?=(count($parts)>1)?end($parts):''?>"/>
			</li>
		<?php endforeach; ?>
		</ol>
		<div class="type-button center">
			<button type="submit"><span><phrase id="SAVE"/></span></button>
			<button class="bin"><span><phrase id="REMOVE_SELECTED"/></span></button>

			<input type="hidden" name="site_id" value="<?=$site_id?>" />
			<input type="hidden" name="scope" value="<?=$scope?>" />
		</div>
	</fieldset>
</form>

<script type="text/javascript">
//<![CDATA[
$('#plugin_select select').change(function() {
	var plugin = $(this).val(),
		random_id = 'plugin_'+(new Date()).getTime();
	$('#plugin_list').append('<li><input type="checkbox"/><input type="hidden" name="plugins[]" value="'+plugin+'"/><label for="'+random_id+'">'+plugin+'</label><input id="'+random_id+'"type="text" name="plugin_methods[]" value=""/></li>').sortable('refresh');

	$(this).val('none');
	return false;
});

$('#plugin_select button.bin').click(function() {
	$('#plugin_list li:has(:checked)').remove();

	return false;
});
//]]>
</script>