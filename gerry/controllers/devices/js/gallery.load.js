$('#device_image_gallery a.image').live('click', function () {
	var $self = $('#device_image_gallery');

	var picture_id = $(this).attr('rel');
	$('.current', $self).removeClass('current');
	$(this).addClass('current')

	var cache = $self.data('cache') || {};
	if (cache[picture_id]) {
		$('div.image img', $self).attr('src', cache[picture_id]);
		return false;
	}

	jQuery.globalEval('var info=' + $self.attr('rel'));

	$.getJSON(info.link, function (data) {
		$.each(data, function (i, item) {
			cache[i] = item.image;
		});
		$self.data('cache', cache);

		$('div.image img', $self).attr('src', cache[picture_id]);
	});
	return false;
});

$('#device_image_gallery a.next, #device_image_gallery a.previous').live('click', function () {
	var $self = $('#device_image_gallery');
	var $element = $(this);

	if (typeof $self.data('current_page') == 'undefined') {
		jQuery.globalEval('var info=' + $self.attr('rel'));
		$self.data('current_page', info.page);
		$self.data('max_page', info.max_pages);
	}
	var current_page = $self.data('current_page');
	var max_page = $self.data('max_page');

	if (($element.hasClass('next') && current_page>=max_page) || ($element.hasClass('previous') && current_page==0)) {
		return false;
	}

	current_page += ($element.hasClass('next') ? 1 : -1);

	if (!$('.page'+current_page, $self).length) {
		$.get( $element.attr('href'), function (response) {
			$('.controls a:not(.page' + current_page + ')', $self).hide();
			$('a.image:last', $self).after( $('.controls a', $(response)) );
		});
	} else {
		$('.page' + current_page, $self).show().siblings('a:not(.page' + current_page + ')').hide();
	}

	$self.data('current_page', current_page);
	return false;
});
