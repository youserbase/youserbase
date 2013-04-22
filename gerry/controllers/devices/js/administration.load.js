/*
$('#upload_form a.add').show().click(function () {
	if ($(this).closest('div').siblings('.type-text').length >= 10) {
		return false;
	}
	$(this).closest('div').after('<div class="type-text"><label><a class="remove" href="#"><phrase id="REMOVE"/></a>&nbsp;</label><input type="file" name="picture_source_path[]"/></div>');

	return false;
});
$('#upload_form a.remove').live('click', function () {
	$(this).closest('div').remove();

	return false;
});
*/

$('#images_administration .sorter').sortable({
	placeholder: 'placeholder',
	helper: 'clone'
});
$('#images_administration button.cancel').click(function () {
	$('#images_administration .sorter').each(function () {
		var i = 0;
		while ($('li.position_' + i, this).length)
		{
			$(this).append($('li.position_' + i, this).remove());
			i += 1;
		}
	});
	return false;
});
$('#images_administration button:not(.cancel)').click(function () {
	var order = [];
	$('#images_administration .sorter li').each(function () {
		order.push($(this).retrieve('picture_id'));
	});
	$.post($(this).retrieve('uri'), {
		device_id : $('#images_administration .picture_holder').retrieve('device_id'),
		'order[]' : order
	}, function (response) {
		$('#images_administration').prepend('<div ondblclick="$(this).remove();">'+response+'</div>');
	});
	return false;
});
