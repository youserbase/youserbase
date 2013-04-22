(function ($) {
	var id_count = 0;
	$('a.ajax').live('click', function (event) {
		if ($(this).is('.confirm')) {
			var message = $(this).attr('title')
				? "\n\n"+'"' + $(this).attr('title') + '"'
				: '';
			if (!confirm( BabelFish('CONFIRMACTION') + message ))
			{
				return false;
			}
		}

		var target = null,
			className = null,
			effect = null,
			closest = null,
			element_to_update = null;

		$(this).attr('className').replace(/target:('[^']+'|\S+)/, function(t0, value) { //'
			target = value;
		});
		target = (target || '').replace(/closest:(.*)/, function(t,x) {
			closest = x;
			return '';
		});
		$(this).attr('className').replace(/class:(?:(\'?)([^\']+)\\1|(\S+))/, function(t0, t1, x, y) {
			className = x || y || null;
		});
		$(this).attr('className').replace(/effect:(?:(\'?)([^\']+)\\1|(\S+))/, function(t0, t1, x, y) {
			effect = x || y || null;
		});

		if (target=='tab' && $(this).closest('.ui-tabs-panel').length) {
			element_to_update = $(this).closest('.ui-tabs-panel');
		} else if (target=='reload' && $(this).closest('.ui-tabs-panel').length) {
			var $tab_element = $('#' + $(this).closest('.ui-tabs-panel').attr('rel'));
			$tab_element.tabs('load', $tab_element.data('selected.tabs'));
		} else if (target=='reload') {
			location.reload();
		} else if (target) {
			element_to_update = $(target);
		} else if (!target && closest) {
			element_to_update = $(this).closest(closest);
		} else if ($(this).next().is('.ajax_content') && $(this).is('.toggle')) {
			$(this).next().hide(effect).queue(function() {
				$(this).remove();
			});
		} else if ($(this).next().is('.ajax_content')) {
			element_to_update = $(this).next();
		} else {
			element_to_update = $('<div style="display: none;"/>').addClass('ajax_content').html('<img src="'+Assets.Image('ajax_indicator_big.gif')+'"/>').insertAfter(this).show(effect);
		}

		if (element_to_update) {
			element_to_update.addClass(className).load($(this).attr('href'));
		}
		return false;
	});
	$('body').live('live_submit', function(event) {
		var self = $(event.target);

		self.ajaxSubmit(function(response, state, element) {
			var $this = $(element[0]),
				target = null,
				className = null,
				effect = null,
				closest = null,
				element_to_update = null;

			$this.attr('className').replace(/target:('[^']+'|\S+)/, function(t0, value) { // '
				target = value;
			});
			target = (target || '').replace(/closest:(.*)/, function(t, x) {
				closest = x;
				return '';
			});
			$this.attr('className').replace(/class:(?:(\'?)([^\']+)\\1|(\S+))/, function(t0, t1, x, y) {
				className = x || y || null;
			});
			$this.attr('className').replace(/effect:(?:(\'?)([^\']+)\\1|(\S+))/, function(t0, t1, x, y) {
				effect = x || y || null;
			});

			if (target=='tab' && $this.closest('.ui-tabs-panel').length) {
				element_to_update = $this.closest('.ui-tabs-panel');
			} else if (target=='reload' && $this.closest('.ui-tabs-panel').length) {
				var $tab_element = $('#' + $this.closest('.ui-tabs-panel').attr('rel'));
				$tab_element.tabs('load', $tab_element.data('selected.tabs'));
			} else if (target=='reload') {
				location.reload();
			} else if (target) {
				element_to_update = $(target);
			} else if (!target && closest) {
				element_to_update = $this.closest(closest);
			} else {
				// TODO: Get rid of this
				$(document).trigger('ajax_submit_complete', response);
			}

			if (element_to_update) {
				element_to_update.html(response);
			}

			$this.trigger('submitted', [response]);
		});
	});

})(jQuery);