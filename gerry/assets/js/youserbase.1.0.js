var Youserbase = {
	CurrentTitle : document.title,
	Growl: {
		messages: [],
		notifier: function() {
			if (Youserbase.Growl.messages.length) {
				while (message=Youserbase.Growl.messages.shift()) {
					$.jGrowl(message.message, {
						sticky: message.sticky || false,
						header: message.header || '',
						theme: message.theme || null,
						life: 5000
					});
				}
			}
		},
		add: function(type, message, header, sticky) {
			Youserbase.Growl.messages.push({
				theme: type,
				message: message,
				header: header||'',
				sticky: sticky||false
			});
		}
	},
	loaded: false,
	consume: function(scope) {
		scope = $(scope || 'body');

		$('form:not(.consumed)', scope).addClass('consumed').submit(function () {
			if ($(this).is('form.validate') && !$(this).valid()) {
				return false;
			}
			if ($(this).is('.ajax')) {
				$(this).trigger('live_submit');
				return false;
			}
		}).find('.validate').validate();

		$('.sortable:not(table)', scope).removeClass('sortable').sortable({});

		$('table.sortable', scope)
			.removeClass('sortable')
			.tablesorter()
			.find('thead tr th')
				.append('<span class="sort-icon asc">&#x25BC;</span><span class="sort-icon desc">&#x25B2;</span>');

		$('ul.tabify', scope).removeClass('tabify').each(function() {
			var li = $('li', this),
				selected_index = Math.max( li.index( li.filter('.ui-tabs-selected') ), 0 ),
				anchor = $('li:eq(' + selected_index + ') a', this),
				selected_url = anchor.attr('href');
			anchor.attr('href', '#'+anchor.attr('title'));

			$(this).parent().tabs({
				spinner: BabelFish('TABSLOADING')+'&hellip;',
				selected: selected_index,
				show : function() {
					$(document).trigger('change_title');
				},
				select : function(event, ui) {
					location.hash = '#'+$(ui.panel).attr('id');
				}
			}).tabs('url', selected_index, selected_url);

			if (location.hash) {
				var hash = location.hash.replace(/^#/, ''),
					a = $('a', li);
				if (a.filter('[title='+hash+']').length) {
					$(this).parent().tabs('select', a.index(a.filter('[title='+hash+']')) );
				}
			}
		});

		$('select.slider:not(.consumed)', scope).addClass('consumed').each(function(index) {
			var select = $(this),
				min = $('option:first', select).val(),
				max = $('option:last', select).val();
			select.after('<div class="ui-slider"><span class="slider-boundary lower">'+min+'</span><span class="slider-boundary upper">'+max+'</span></div>').next().slider({
				min: min,
				max: max,
				startValue: $(this).find('option:selected').val(),
				stepping: $(this).find('option:odd:first').val()-$(this).find('option:even:first').val(),
				slide: function(e, ui) {
					select.val(ui.value);
				}
			}).change(function() {
				$(this).next().slider('moveTo', $(this).val());
			});
		});

		$('.add_datepicker', scope)
			.removeClass('add_datepicker')
			.datepicker({
				dateFormat : 'yy-mm-dd',
				changeYear : true,
				changeMonth : true,
				yearRange : '1900:'+(new Date()).getFullYear()
			});

		$('.tipsify', scope).removeClass('tipsify').each(function() {
			var gravity = $(this).hasClass('south')
				? 's'
				: $(this).hasClass('west')
				? 'w'
				: $(this).hasClass('east')
				? 'e'
				: 'n';
			$(this).tipsy({gravity: gravity});
		});

		$('textarea.autogrow', scope)
			.removeClass('autogrow')
			.autogrow();

		$('.onchange_submit', scope)
			.removeClass('onchange_submit')
			.change(function() {
				$(this).submit();
			})
			.find(':submit,:button')
				.remove();

		$('select.select_or_input', scope).removeClass('select_or_input').change(function() {
			if (parseInt($(this).val())<0) {
				$(this).next().fadeIn().attr('disabled', false);
			} else {
				$(this).next().fadeOut().attr('disabled', true);
			}
		}).change();

		$('.highlight', scope).removeClass('highlight').mouseenter(function() {
			if ($(this).hasClass('column') || !$(this).hasClass('row')) {
				var cell_count = $(this).parent().children().index(this) + 1;
				$('tr > *:nth-child(' + cell_count + ')', $(this).closest('tbody')).addClass('highlighted');
			}
			if ($(this).hasClass('row') || !$(this).hasClass('column')) {
				$(this).closest('tr').addClass('highlighted');
			}
		}).mouseleave(function() {
			if ($(this).is('td')) {
				$('td.highlighted, th.highlighted', $(this).closest('tbody')).removeClass('highlighted');
			} else {
				$('.highlighted', $(this).closest('tbody')).removeClass('highlighted');
			}
		});

		$('div.pagination:not(.consumed)', scope).addClass('consumed').each(function() {
			YB_Pagination.initialize(this);
		});

		$('.accordion').removeClass('accordion').accordion({
			active: false,
			alwaysOpen: false,
			autoHeight: false,
			clearStyle: true,
			header: '.ui-accordion-header',
			navigation: true
		});
	}
}

$.jGrowl.defaults.position = 'bottom-left';

$.datepicker.setDefaults({
	showOn : 'both',
	buttonImageOnly : true,
	buttonImage : Assets.Image('famfamfam/calendar_view_day.png'),
	buttonText : BabelFish('CALENDAR')
});

$.tablesorter.defaults.widgets = ['zebra'];
$.tablesorter.defaults.widgetZebra = {css : ['a0', 'a1']};
$.tablesorter.addParser({
	id : 'timestamp',
	is : function (s) {
		return false;
	},
	format : function(s) {
		return $.trim(s).replace('&nbsp;', '').length ? $(s).attr('rel') : 0;
	},
	type : 'numeric'
});
