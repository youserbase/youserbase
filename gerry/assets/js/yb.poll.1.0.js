jQuery(function ($) {
	if (!$('meta[name=PollAdress]').length) {
		return;
	}

	var last_poll = null,
		url = $('meta[name=PollAdress]').attr('content'),
		frequency = $('meta[name=PollFrequency]').attr('content') || 30000,
		timeout = null;

	function poll() {
		if (last_poll && ((new Date()).getTime() - last_poll) > frequency) {
			$.getJSON(url, function (json) {
				$.each(json, function (key) {
					if (json[key] !== $.trim($('#poll_' + key).text())) {
						$('#poll_' + key).text(json[key]).parent().effect('pulsate', {times: 2}, 1000);
					}
				});
				last_poll = (new Date()).getTime();
			});
		}
		window.clearTimeout(timeout);
		timeout = window.setTimeout(arguments.callee, frequency);

		last_poll = (new Date()).getTime();
	}

	$(window.document).blur(function () {
		window.clearTimeout(timeout);
	}).focus(poll);

	poll();
});