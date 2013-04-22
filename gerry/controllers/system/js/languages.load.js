(function ($) {
	var languages = [],
		result = $('<ul id="language_list"></ul>'),
		$fragment = $('#language_switch').remove();

	$('select option', $fragment).each(function () {
		languages.push({
			link: $(this).val(),
			value: $(this).text(),
			code: $(this).retrieve('language')
		});
	});
	$.each(languages, function (index) {
		$('<a/>')
			.attr('href', this.link)
			.text(this.value)
			.wrap($('<li class="flags-sprite front"/>').addClass(this.code).addClass('r' + index).addClass('a' + Math.floor(index/2)%2))
			.parent()
			.appendTo( result );
	});

	$('#top_slider > div.languages').html(result);

	$('#nav_top_k li.languages').remove();
})(jQuery);
