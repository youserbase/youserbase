<form id="tab_settings" action="<?=FrontController::GetLink()?>" method="post" class="ajax yform">
	<fieldset class="site_selector">
		<legend><phrase id="SITE"/></legend>

		<?=$this->render_partial('site_select', array('selected'=>$site_id))?>
		<button type="submit" class="add"><span><phrase id="ADD"/></span></button>
		<button type="submit" class="load"><span><phrase id="LOAD"/></span></button>
	</fieldset>
	<fieldset class="tabs">
		<legend><phrase id="TABS"/></legend>
		<ol class="sortable">
		<?php foreach ($site_tabs as $index=>$data): ?>
			<li>
				<input type="checkbox"/>

				<input type="hidden" name="tabs[]" value="<?=$index?>"/>

				<span><?=implode(' / ', $all_tabs[$index])?></span>

			</li>
		<?php endforeach; ?>
		</ol>
	</fieldset>
	<p style="text-align: center;">
		<button type="submit"><span><phrase id="SAVE"/></span></button>
		<a href="#" class="remove"><phrase id="REMOVE_SELECTED"/></a>

		<input type="hidden" name="site_id" value="<?=$site_id?>"/>
	</p>
</form>

<script type="text/javascript">
//<![CDATA[
$('#tab_settings .site_selector button.add').click(function () {
	var	option = $('#tab_settings .site_selector select option:selected:first'),
		text = option.prevAll('.module:first').text() + ' / ' + option.prevAll('.controller:first') + ' / ' + option.text(),
		value = option.val();

	$('#tab_settings .tabs ol')
		.append('<li><input type="checkbox"/><input type="hidden" name="tabs[]" value="' + value + '"/><span>' + text + '</span></li>')
		.sortable('refresh');

	return false;
});
$('#tab_settings .site_selector button.load').click(function () {
	$('#tab_settings .tabs ol').empty();

	$.getJSON('<?=FrontController::GetLink('Tabs_Load')?>', {
		site_id : $('#tab_settings .site_selector select option:selected:first').val()
	}, function (json) {
		$.each(json, function (value, location) {
			location = location.module + ' / ' + location.controller + ' / ' + location.method;
			$('<li><input type="checkbox"/><input type="hidden" name="tabs[]" value="' + value + '"/><span>' + location + '</span></li>').appendTo('#tab_settings .tabs ol');
		});
	});

	return false;
});

$('#tab_settings a.remove').click(function () {
	$('#tab_settings .tabs ol li:has(input:checked)').remove();

	return false;
});
//]]>
</script>