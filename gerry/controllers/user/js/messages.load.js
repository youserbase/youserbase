$('input[name="all_messages"]:checkbox').live('click', function() {
	var $inputs = $(this).parents('table').find('input[name="messages[]"]:checkbox').attr('checked', $(this).attr('checked'));
	$inputs.parents('tr').removeClass('marked').end().filter(':checked').parents('tr').addClass('marked');
});
$('input[name="messages[]"]').live('click', function() {
	if ($(this).attr('checked')) {
		$(this).parents('tr').addClass('marked');
	} else {
		$(this).parents('tr').removeClass('marked');
	}
});
$('a.message_display:not(.open)').live('click', function() {
	YB_Messages.open($(this));
	return false;
});
$('a.message_display:.open').live('click', function() {
	YB_Messages.close($(this));
	return false;
});

$('table.message_list input').live('click', function() {
	return $(this).parents('form').find('input[name="messages[]"]:checkbox:checked').length;
});

$('table.message_list input[name="unread"]').click(function(event) {
	var form = $(this).parents('form');
	var parameters = form.find('input[name="messages[]"]:checkbox:checked').serializeArray();
	if (!parameters.length) {
		return;
	}
	parameters.push({name: $(this).attr('name'), value: $(this).attr('value')});
	$.post(form.attr('action'), parameters, function(data, status) {
		if (status=='success' && data && data.success) {
			form.find('input[name="messages[]"]:checkbox:checked').parents('tr').addClass('new').find('a.message_display.open').each(function() {
				YB_Messages.close($(this).parents('tr').find('a.message_display.open'))
			});
		}
	}, 'json');
	return false;
});
$('table.message_list input[name="read"]').click(function(event) {
	event.preventDefault();

	var form = $(this).parents('form');
	var parameters = form.find('input[name="messages[]"]:checkbox:checked').serializeArray();
	if (!parameters.length) {
		return;
	}
	parameters.push({name: $(this).attr('name'), value: $(this).attr('value')});
	$.post(form.attr('action'), parameters, function(data, status) {
		if (status=='success' && data && data.success) {
			form.find('input[name="messages[]"]:checkbox:checked').parents('tr').removeClass('new');
		}
	}, 'json');
});
