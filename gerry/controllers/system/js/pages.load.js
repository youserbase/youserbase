/*global jQuery*/
(function ($) {
	$('.systempage a.update').live('click', function () {
		var $container = $(this).closest('.systempage'),
			href = $(this).attr('href');
		$.get(href, function (response) {
			$container
				.addClass('editing')
				.children(':not(.options)')
					.toggle()
					.end()
				.append(response);
			$('button.cancel:visible', $container).one('click', function () {
				$container
					.removeClass('editing')
					.children(':not(.options)')
						.toggle()
						.not(':visible')
						.remove();
				return false;
			});
			$('button:not(.cancel):visible', $container).one('click', function () {
				$.post(href, {
					content : $(this).closest('form').find('textarea[name="content"]').val()
				}, function(response) {
					$container
						.removeClass('editing')
						.children(':not(.options)')
							.remove()
							.end()
						.append(response);
				});
				return false;
			});
		});
		return false;
	});

	$('.systempage .expando').css({cursor: 'pointer'}).live('click', function () {
		var $this = $(this),
			state = $this.hasClass('closed') ? 'open' : 'closed';
		do {
			$this = $this.removeClass('open closed').addClass(state).next();
		} while ($this.is('p'));
	}).click();
})(jQuery);