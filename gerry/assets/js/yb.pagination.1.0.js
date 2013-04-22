var YB_Pagination = {
	options: {
		origin: ['top', 'left']
	},
	initialize: function(element) {
		$('.divider', element).each(function() {
			var info = $(this).attr('rel').split('-', 4);

			var box = $('<div/>').addClass('more').hide();
			for (var i=parseInt(info[0]); i<=info[1]; i++) {
				box.append($('<a/>').attr({
					href: info[3]+i,
					className: info[2]
				}).text(i+1)).append("\n");
			}

			$(this).css({cursor: 'pointer'}).prepend(box).click(function(event) {
				$('div.more:visible', event.target).hide('scale', YB_Pagination.options, null, function() {
					YB_Pagination.unregister_hide();
				});
				if ($('div.more', this).is(':not(:visible)')) {
					$('div.more:visible').hide('scale', YB_Pagination.options);
					$('div.more', this).show('scale', YB_Pagination.options, null, function() {
						YB_Pagination.register_hide();
					});
				}
			});
		});
	},
	click_handled: false,
	click_handler: function(event) {
		if ($(event.target).parents().andSelf().is('.divider')) {
			return;
		}
		YB_Pagination.unregister_hide();
		$('div.more:visible').hide('scale', YB_Pagination.options, 'fast');
	},
	register_hide: function() {
		if (YB_Pagination.click_handled) {
			return;
		}
		YB_Pagination.click_handled = true;
		$('body').click(YB_Pagination.click_handler);
	},
	unregister_hide: function() {
		if (!YB_Pagination.click_handled) {
			return;
		}
		YB_Pagination.click_handled = false;
		$('body').unbind('click', YB_Pagination.click_handler);
	}
}