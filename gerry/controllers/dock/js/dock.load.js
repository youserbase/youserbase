var views = $('#dock #view_container div.view');
if (views.length>1) {
	var index = $.cookie('dock_view')||0;
	views.filter(':eq('+index+')').siblings().hide();
	$('#rotate_dock').css({cursor: 'pointer'}).show().click(function() {
		if (!views.filter(':visible').hide().next().show().length)	{
			views.filter(':first').show();
		}
		var index = Math.max(views.index(views.filter(':visible')), 0);
		$.cookie('dock_view', index, {path: $('base').attr('href').replace(/^http:\/\/([^/]+)/, '')});
	});
}

$('#dropbox').hover(function() {
// Hover-Effekt für die Dropbox erzeugen
	if (!$('.dropbox-device', this).length) {
		return;
	}
	$(this).trigger('refresh');
	$('#dropbox_content:not(:visible)', this).show('slide', {direction: 'down'});
}, function() {
	$('#dropbox_content:visible', this).hide('slide', {direction: 'down'});
}).droppable({
// Dropbox zum Droppable machen
	accept: '.device-link',
	activeClass: 'active',
	hoverClass: 'hovered',
	drop: function(event, ui) {
		$(this).append('.');
	}
}).bind('refresh', function() {
//
	var device_count = $('.dropbox-device:checked', this).length;
	$(':button[value=compare]', this).attr('disabled', device_count<1);
	$(':button[value=remove]', this).attr('disabled', device_count<1);
}).trigger('refresh').find(':button[value=remove]').click(function(event) {
//
	var li = $('#dropbox_devices > li:has(:checked)').remove();
	$('#dropbox_count').load(GLOBALS.URLS['Dropbox-Remove'], $(':checked', li).serializeArray());

	return false;
});


$('body').change(function(event) {
	if ($(event.target).is(':checkbox.dropbox-device')) {
		$('#dropbox').trigger('refresh');
	}
}).ajaxComplete(function(event, transport) {
	if (transport && transport.getResponseHeader('X-Dropbox-Refresh')) {
		$('#dropbox_devices').load(GLOBALS.URLS['Dropbox-Refresh']+' ul li');
	}
});

$('a.device-link').draggable({
	revert: true,
	helper: 'clone',
	containment: 'document',
	opacity: 0.5,
	zIndex: 10000,
	delay: 300,
	start: function() {
//		console.log('draggable-start');
	}
});
