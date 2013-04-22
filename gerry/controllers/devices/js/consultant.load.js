$('#consultant').bind('update', function() {
	$('#consultant_devices > li:visible:gt(' + ($(this).data('max_devices') - 1) + ')').css('display', 'none');
	$('#consultant_devices > li:not(:visible):gt(' + ($(this).data('max_devices')*4 - 1) + ')').remove();
}).bind('poll', function() {
	var selection = {};

	$('select,input:checked', this).each(function() {
		var value = $(this).val();
		var attribute = $(this).attr('rel');

		if (value != -1) {
			selection[attribute] = value;
		};
	});

	var hash = [];
	for (var key in selection) {
		hash.push(key+'='+selection[key]);
	};
	location.hash = hash.join('&')||' ';

	$('#consultant_devices > li').each(function() {
		var json = $(this).data('data');
		if ( !json ) {
			eval( 'json='+$(this).attr('rel')+';' );
			$(this).data('data', json);
		};

		var show = true;
		for (var attribute in selection) {
			show &= (json[attribute] == selection[attribute]);
		};
		$(this).css('display', show?'':'none');
	});

	if ($('#consultant_devices > li:visible').length < $(this).data('max_devices')) {
		selection['device_ids[]'] = jQuery.map($('#consultant_devices > li:visible'), function(value) {
			var data = $(value).data('data');
			return data.d;
		});

		$('#consultant_loading').show();

		$.post($(this).attr('action'), selection, function(data, status) {
			$('#consultant_devices').append(data);
			$('#consultant_loading').hide();
			$(this).trigger('update');
		});
	};

	$(this).trigger('update');
}).submit(function() {
	return false;
}).find('select,input[type=radio]').change(function() {
	$('#consultant').trigger('poll');
});

if ($('#consultant').length) {
	eval( 'var $max = ' + $('#consultant_devices').attr('rel') + ';' );
	$('#consultant').data('max_devices', $max.size);

	$('#consultant button:last').parent().next('dd').andSelf().remove();

	if (location.hash) {
		$('#consultant_loading').show();

		var hash = location.hash;
		if (hash.substr(0,1)=='#') {
			hash = hash.substr(1);
		};
		var selections = hash.split('&');
		for (var i=0; i<selections.length; i++) {
			var key = selections[i].split('=')[0];
			var value = selections[i].split('=')[1];

			$('select[rel='+key+']').find('option[value='+value+']').attr('selected', true);
			$('input[rel='+key+'][value='+value+']').attr('checked', true);
		};
		$('#consultant').trigger('poll');
	};
};
