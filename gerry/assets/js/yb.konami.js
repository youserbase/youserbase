/*global console, jQuery, window*/
(function ($) {
	var keys = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
		index = 0;
	$(window).keyup(function (event) {
		index += (event.keyCode === keys[index]) ? 1 : -index;
		if (index >= keys.length) {
			index = 0;
			console.log('Konami cheat code enabled, you now have infinite lives and credits');
		}
	});
})(jQuery);
