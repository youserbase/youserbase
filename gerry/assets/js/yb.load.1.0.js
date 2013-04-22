// Get rid of all(?) Firebug's console related bugs
if (!window.console) {
	var empty_function = function () {};
	window.console = {
		log : empty_function,
		error : empty_function,
		dir : empty_function,
		info : empty_function
	};
}

$.debug(true);

$('.dobber:not(.system_error)').remove().each(function () {
	var header_message = $('div.label', this).text(),
		// Ugly as hell, I know. JSLint did hurt my feelings... :(
		theme = $(this).hasClass('error') ? 'error' : $(this).hasClass('success') ? 'success' : $(this).hasClass('notice') ? 'notice' : null;
	$('ul li', this).each(function () {
		Youserbase.Growl.add(theme, $(this).text(), header_message, false);
	});
});

$('.dobber').dblclick(function () {
	$(this).remove();
});

$('li.top_slider').show().find('a').click(function () {
	var what = $(this).attr('className');
	if (!$('#top_slider .' + what).is(':visible')) {
		$('#top_slider > div:not(.label)').hide();
		$('#top_slider .label > span').hide();
		$('#top_slider .' + what).show();
		if (!$('#top_slider').is(':visible')) {
			$('#top_slider').slideDown();
		}
	} else {
		$('#top_slider').slideToggle();
	}
	return false;
});
$('#top_slider').hide();

Youserbase.consume();

window.setInterval(Youserbase.Growl.notifier, 250);

$('.js_remove').remove();

$('#query').inputHint();

$('.requires_login').addClass('lightbox');

$('a.external').live('click', function () {
	return !window.open(this.href);
});

$('.confirm').live('click', function () {
	var message = $(this).attr('title') ? "\n\n" + '"' + $(this).attr('title') + '"' : '';
	return window.confirm(BabelFish('CONFIRMACTION') + message);
});

$('.disabled').live('click', function () {
	return false;
});

$('span.phrase').live('click', function (event) {
	if (!(event.metaKey && event.shiftKey)) {
		return;
	}
	$(this).not('.editable').addClass('editable').editable(GLOBALS.URLS['Babelfish-Update'], {
		submitdata : {
			phrase_id : $(this).retrieve('phrase_id'),
			language : $(this).retrieve('language') || GLOBALS.Language
		},
		loadurl : GLOBALS.URLS['Babelfish-Load'],
		loaddata : {
			phrase_id : $(this).retrieve('phrase_id'),
			'default' : $(this).text(),
			language : $(this).retrieve('language') || GLOBALS.Language
		},
		name : 'translation',
		type : 'text',
		onblur : 'cancel',
		submit : 'OK',
		select : true,
		event : 'edit',
		cssclass : 'phrase_translator',
		style : 'inherit',
		callback : function (value, settings) {
			$('.phrase[class~="stored:phrase_id:' + $(this).retrieve('phrase_id') + '"][class~="stored:language:' + $(this).retrieve('language') + '"]').text(value);
		}
	}).end().trigger('edit');

	return false;
});

$('form.phrase_translator input').live('click', function () {
	return false;
}).live('keypress', function (event) {
	if (event.keyCode === 13) {
		$(this).closest('form').submit();
		return false;
	}
});

$(document).bind('change_title', function () {
	document.title = Youserbase.CurrentTitle;
});

var throbber_timeout;
$('body').ajaxStart(function () {
	var self = $(this).css('cursor', 'progress');
	throbber_timeout = window.setTimeout(function () {
		self.css('cursor', 'wait').append($('<iframe class="throbber" src="throbber.php"/>').hide());
		throbber_timeout = window.setTimeout(function () {
			self.trigger('ajaxStop');
		}, 5000);
	}, 300);
}).ajaxStop(function () {
	$(this).css('cursor', '').find('iframe.throbber').remove();
	window.clearTimeout(throbber_timeout);
}).ajaxSend(function (event, transport, options) {
	if (typeof pageTracker !== 'undefined' && !options.url.match('Poll')) {
		pageTracker._trackPageview('/' + options.url.replace(/^\//, ''));
	}
}).ajaxComplete(function (event, transport) {
	if (!transport) {
		return;
	}

	if (transport.getResponseHeader('X-Title')) {
		Youserbase.CurrentTitle = decodeURIComponent(transport.getResponseHeader('X-Title'));
	}

	if (transport.getResponseHeader('X-Refresh')) {
		var url = transport.getResponseHeader('X-Refresh');
		if (!!url.match(/^https?:\/\//)) {
			window.location.href = url;
		} else {
			window.location.reload();
		}
	}

	if (transport.getResponseHeader('X-Babelfish')) {
		var json = json_parse(transport.getResponseHeader('X-Babelfish')),
			total = parseInt($('#babelfish--untranslated').text().replace('.', ''), 10) + parseInt($('#babelfish--translated').text().replace('.', ''), 10);
		$('#babelfish--untranslated').text(json.untranslated);
		$('#babelfish--translated').text(json.translated);
		if (json.untranslated + json.translated - total) {
			$('#babelfish--untranslated').parent().effect('pulsate', {times: 2}, 1000);
		}
	}
	if (transport.getResponseHeader('X-Dobber')) {
		var plain_json = transport.getResponseHeader('X-Dobber'),
			message = false,
			json = json_parse(plain_json),
			type = null;
		for (type in json) {
			if (true) {
				while (message = json[type].messages.shift()) {
					Youserbase.Growl.add(type, message, json[type].header, json[type].header === 'error');
				}
			}
		}
	}
	if (transport.responseText.length) {
		Youserbase.consume();
	}
});

Youserbase.loaded = true;