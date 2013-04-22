/*global jQuery*/
(function ($) {
	$('#system_system_index #newuser #newuser_login').click(function () {
		$('#signin_menu a.trigger').click();
		return false;
	});

	var timeout = null;
	$('#intro_youserbase span').hover(function () {
		var index = $(this).parent().children().index($(this));
		$('#intro_youserbase .kernel > div:eq(' + (index + 1) + ')').show().siblings().hide();
		clearTimeout(timeout);
	}, function (event) {
		if (event.shiftKey) {
			return;
		}
		timeout = setTimeout(function () {
			$('#intro_youserbase .kernel > div:eq(0)').show().siblings().hide()
		}, 600);
	});
})(jQuery);
