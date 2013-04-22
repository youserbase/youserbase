$('#development_console').addClass('adjusted').prepend(
	$('<span/>').text('close').addClass('close').click(function() {
		$('#development_console').fadeOut('slow', function() { $(this).remove(); });
	})
).one('mouseover', function() {
	$(this).animate({
		left: '+=89%',
		right: '-=89%'
	}, 'fast');
});
$('#debug_toggle').bind('submitted', function(event, response) {
	var json = json_parse(response);
	if (!$('#debug').length) {
		window.location.reload();
		return;
	}
	$('#debug').toggle(json.debugmode);
});
$('#debug dl dt').click(function() {
	if (!$(this).next().is('dd')) {
		return;
	}
	$(this).next().toggle();
});