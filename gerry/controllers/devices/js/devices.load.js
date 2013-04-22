$('.cooliris').live('click', function() {
	if (typeof PicLensLite != 'undefined') {
		PicLensLite.start({feedUrl: $(this).attr('rel')});
		return false;
	};
});