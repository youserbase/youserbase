/*$('#searchbar:not(.adjusted)').addClass('adjusted').bind('display', function () {
	if (!$(this).data('results')) {
		return;
	} else if (!$('#search_results').length) {
		$(this).trigger('load_template');
		return;
	}

	var $scope = $('#search_results'),
		results = $(this).data('results'),
		max = 30,
		json = {};

	$('ul.products, ul.manufacturers, ul.device_types, ul.tags', $scope).empty();

	if (!results.models) {
		$('#search_count').text('0');
		$('.not_found', $scope).show();
		$('.found', $scope).hide();
		$('#search_results:not(:visible)').slideDown();
		$('.layer', $scope).fadeOut();
		return;
	}

	$('.not_found', $scope).hide();
	$('.found', $scope).show();

	max = $('#search_results .found').retrieve('max');

	function spread(data, target) {
		$.each(data, function (index) {
			$('<li class="sr p' + Math.floor(index / max) + '"><a href="' + this.url + '">' + this.name + '</a></li>').appendTo(target);
		});
	}
	spread(results.models, $('ul.products', $scope));
	spread(results.manufacturers, $('ul.manufacturers', $scope));
	spread(results.types, $('ul.device_types', $scope));
	spread(results.tags, $('ul.tags', $scope));

	$('#search_count').text( results.models.length + results.manufacturers.length + results.tags.length );

	$('#searchbar').trigger('paginate');

	$('.layer', $scope).fadeOut();
	$('#search_results:not(:visible)').slideDown();
}).bind('load_template', function () {
	$.get($(this).attr('action'), function (data) {
		if ($('#search_results').length) {
			return;
		}
		$(data).hide().insertAfter('#top_bar');
		$('#searchbar').trigger('display');
		$('#search_results .layer').css({opacity: $('#search_results .layer').css('opacity')});
		$('#search_result_close').click(function () {
			$('#searchbar').trigger('hide_results');
			return false;
		});
	}, 'html');
}).bind('hide_results', function () {
	$('#search_results').slideUp();
	$('#searchbar input.text').focus();
}).bind('paginate', function () {
	$('#search_results a.search_previous, #search_results a.search_next')
		.css('visibility', 'visible')
		.parent().data('current_page', 0);

	$('#search_results .sr:not(.p0)').hide();

	$('#search_results .search_previous').css('visibility', 'hidden').parents('li').each(function () {
		if (!$('li.sr.p1', this).length) {
			$('.search_next', this).css('visibility', 'hidden');
		}
	});
}).submit(function () {
	return false;
}).find('input[type=text]').keyup(function (event) {
	if (event.keyCode === 27 && $('#search_results').is(':visible')) {
		$('#searchbar').trigger('hide_results');
		return false;
	}

	var needle = $(this).val();
	var minlength = $(this).attr('minlength') || 3;
	if (0 + needle.length < minlength) {
		window.clearTimeout($(this).data('timeout'));
		$(this).data('timeout', window.setTimeout(function () {
			$('#search_results').slideUp();
		}, 500));
		return;
	} else if (needle === $(this).data('needle') || needle === ($(this).attr('hint') || $(this).attr('defaultValue'))) {
//		window.clearTimeout($(this).data('timeout'));
		return;
	}

	$(this).data('needle', needle);
	var action = $(this).parents('form').attr('action');

	window.clearTimeout($(this).data('timeout'));

	$('#search_results .layer').fadeIn();
	$(this).data('timeout', window.setTimeout(function () {
		$.get(action, {
			needle : needle
		}, function (data) {
			$('#searchbar').data('results', data).trigger('display');
		}, 'json');
	}, 500));

}).keyup().next('input.button').remove();


(function () {
	function display_search_results(element, direction) {
		$(element).siblings('a').css('visibility', 'visible');

		var $list = $(element).parents('li').find('li.sr').hide(),
			current_page = $(element).parent().data('current_page') + direction;

		$list.filter('.p' + current_page).show();
		$(element).parent().data('current_page', current_page);

		if (!$list.filter('.p' + (current_page + direction)).length)
		{
			$(element).css('visibility', 'hidden');
		}

		return false;
	}

	$('#search_results a.search_next').live('click', function () {
		return display_search_results(this, +1);
	});
	$('#search_results a.search_previous').live('click', function () {
		return display_search_results(this, -1);
	});
})();*/