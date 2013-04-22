if ($('#feedback_beta').length) {
	$('#feedback_beta').bind('open.yb', function() {
		$(this).animate({left:'0px'}, 'normal', function() {
			$('textarea', this).focus();
		});
	}).bind('close.yb', function(event, empty) {
		$('#feedback_beta').animate({left:'-452px'}, 'fast', function() {
			$('.response', this).remove();
			$('form', this).show();
		}).find('textarea').blur();
	}).bind('empty.yb', function() {
		$('textarea', this).val('');
	}).hover(function() {
		$(this).trigger('open');
	}, function() {
		$(this).trigger('close');
	}).find('button.cancel').click(function() {
		$('#feedback_beta').trigger('close').trigger('empty');
		return false;
	}).end().find('form').submit(function() {
		return !!$('textarea', this).val().match(/\S/);
	}).bind('submitted', function(event, response) {
		$(this)
			.before(
				$('<div class="response"/>')
					.css({width:$(this).outerWidth(), height:$(this).outerHeight()})
					.html( $('<div/>').html(response))
			).hide()
			.trigger('empty');
	});
/*
	var initial_y = $('#feedback_beta').position().top;
	var scroll_timeout = null;
	$(window).scroll(function(event) {
		if (scroll_timeout) {
			clearTimeout(scroll_timeout);
		}
		scroll_timeout = setTimeout(function() {
			$('#feedback_beta').animate({top: initial_y + $(window).scrollTop()}, 'normal');
		}, 250);
	});
*/
}