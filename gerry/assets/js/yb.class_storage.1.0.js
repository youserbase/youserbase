/**!
 * <tag class="foo stored:<key>:<value>">...</tag>
 */
(function ($) {
	if ($.fn.retrieve || $.fn.store) {
		return;
	}
	$.fn.retrieve = function (key) {
		var $this = $(this),
			value = $this.data(key);
		if (!value && $this.attr('class').indexOf('stored:' + key + ':') !== -1) {
			value = $this.attr('class').match('stored:' + key + ':(\\S+)')[1];
			$this.removeClass('stored:' + key + ':' + value);

			value = decodeURI(value);
			if (!value.match('^(null|true|false|{.*}|".*"|-?\\d+(?:\\.\\d+)?)$')) {
				value = '"' + value + '"';
			}
			eval('value = (' + value + ');');

			$this.data(key, value);
		}
		return value;
	};
	$.fn.store = function (key, value) {
		$(this).data(key, value);
	};
})(jQuery);