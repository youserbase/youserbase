$('body').click(function(event) {
	if (!$(event.target).is('.parent_selector')) {
		return;
	}
	var site_id = $(event.target).attr('rel');
	var parent_id = null;
	if ($(event.target).next('input:hidden').length) {
		parent_id = $(event.target).next('input:hidden').val();
	}

	var selector = $('#parent_selector');
	if (selector.data('belongs_to')) {
		$(selector.data('belongs_to')).filter(':hidden').show();
	}
	selector.remove();

	$('option.method', selector).attr('selected', false).attr('disabled', false).filter('option.method[value='+site_id+']').attr('disabled', true);
	if (parent_id) {
		$('option.method[value='+parent_id+']', selector).attr('selected', true);
	}
	$(event.target).hide().before(selector);

	selector.show().data('belongs_to', event.target);

	selector.focus().change(function() {
		var span = $(this).data('belongs_to');
		var site_id = $(span).attr('rel');
		$(span).next('input:hidden').remove();
		if ($(this).val()!='-1') {
			$(span).after('<input type="hidden" name="parent_sites['+site_id+']" value="'+$(this).val()+'"/>');
		}
		var text = $('option:selected', this).text();

		var index = $('option', this).index($('option:selected', this));
		if (index>0) {
			text = $('option:lt('+index+')', this).filter('.controller:last').text()+'/'+text;
			text = $('option:lt('+index+')', this).filter('.module:last').text()+'/'+text;
		}

		$(span).show().text(text);

		$(this).hide();
	});
});