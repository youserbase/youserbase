var YB_Messages = {
	open: function(element) {
		element = $(element);
		element.after($('<div/>').addClass('message_content').hide().load(element.attr('href'), function() {
			element.addClass('open').parents('td').addClass('open').parents('tr').removeClass('new');
			$(this).show('blind');
		}));
	},
	close: function(element) {
		element = $(element);
		element.next().hide('blind', {}, 'normal', function() {
			element.removeClass('open').parents('td').removeClass('open').end().next().remove();
		});
	}
};