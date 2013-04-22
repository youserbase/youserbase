function BabelFish(id) {
	return ({
		RATING_0 : 'gar nix',
		RATING_1 : 'schlecht',
		RATING_2 : 'naja',
		RATING_3 : 'mittel',
		RATING_4 : 'gut',
		RATING_5 : 'bestens',
		IS_DONE : 'Du hast hier schon bewertet. Um Deine Bewertung zu �ndern, doppelklicke dieses Element.',
		DISABLED : 'Du musst angemeldet sein, um bewerten zu k�nnen.'
	})[id]||('untranslated: '+id);
}
var GLOBALS = {
	URLS : '/login/'
}
(function ($) {
	var w = 200;
	//six values caused of the zero-rating=> counting zero, one, two, three, four, five => 6 values
	var indicator = [
		BabelFish('RATING_0'),
		BabelFish('RATING_1'),
		BabelFish('RATING_2'),
		BabelFish('RATING_3'),
		BabelFish('RATING_4'),
		BabelFish('RATING_5')
	];
	var isdone = '<strong style="color: black">You voted already. To vote again doubleclick</strong>';
	var disable = '<a href="/login/" style="color: red; font-weight: 900">To vote you have to log in!</a>';

	$('.ratingstar').live('mouseover', function (event) {
		var $t = $(this);
		$('.ratingstars_peak', $t).stop(true);
		//checkout which status this rating has
		if ($t.hasClass('isdone')) {
			$('.ratingstars_user', $t).show();
			$('.ratingstars_average', $t).addClass('ratingstar_grey');

			$('.indicator > span:first', $t).hide();
			$('.indicator > span:last', $t).html(isdone).show();
			return;
		}
		if ($t.hasClass('disable')) {
			$('.ratingstars_average', $t).addClass('ratingstar_used');
			$t.css('cursor', 'default');

			$('.indicator span:first', $t).hide();
			$('.indicator span:last', $t).html(disable).show();
			return;
		}
		$('.ratingstars_user', $t).hide();
		$('.ratingstars_peak', $t).show();
		$('.ratingstars_average', $t).addClass('ratingstar_grey');
	});
	$(".ratingstar:not(.isdone):not(.disable)").live("mousemove", function(event){
		$t = $(this);
		var starw = $t.hasClass('bigstar') ? 32 : 17;
		var x = event.clientX - $t.offset().left;
		x = Math.round(Math.round(x / (starw / 2)) * (starw / 2));
	    //the score (range from 0 to 10 (int))
	    var score = Math.round(x / starw);
	    $('.indicator > span:first', $t)
	    	.hide()
	    	.siblings()
	    		.show()
	    		.empty()
	    		.append(indicator[score]);
	    $('.ratingstars_peak', $t).width(x);
	});
	$(".ratingstar:not(.disable)").live("mouseout", function(e){
	    $t = $(this);
	    if ($t.hasClass('isdone')) {
			$('.ratingstars_user', $t).hide();
			$('.ratingstars_peak', $t).width($('.ratingstars_user', $t).width()).addClass('ratingstar_used');
			$('.ratingstars_peak', $t).animate({
				width: $('.ratingstars_average', $t).width() + "px"
			}, 1000, 'swing', function(){
				$('.indicator > span:first')
					.show()
					.siblings()
						.hide()
						.text('');
				$('.ratingstars_peak', $t).width(0).removeClass('ratingstar_used');
				$('.ratingstars_average'. $t).show().removeClass('ratingstar_grey ratingstar_used');
			});
		} else {
			$('.ratingstars_peak', $t).animate({
				width: $('.ratingstars_average', $t).width() + "px"
			}, 1000, 'swing', function() {
				$('.indicator > span:first')
					.show()
					.siblings()
						.hide()
						.text('');
				$('.ratingstars_average', $t).show().removeClass('ratingstar_grey ratingstar_used');
				$('.ratingstars_user', $t).hide();
				$('.ratingstars_peak', $t).width(0);
			});
		}
	});
	$(".ratingstar").live("click", function (event) {
	    $t = $(this);
		//wash coordinates
		x = event.clientX - $t.offset().left;
		starw = $t.hasClass('bigstar') ? 32 : 17;

//		x = Math.round(Math.round(x / (starw / 2)) * (starw / 2));
		$t.addClass('isdone')

		$('.ratingstars_average', $t).hide().removeClass('ratingstar_grey ratingstar_used');
		$('.ratingstars_user', $t).show().width(x);
		$('.ratingstars_peak', $t).width(0);
	});
	$(".ratingstar").live("dblclick", function(event){
		var $t = $(this).removeClass('isdone');
		var starw = $t.hasClass('bigstar') ? 32 : 17;
//		x = Math.round(Math.round(x / starw / 2) * starw / 2);
		$('.ratingstars_peak', $t).width(event.clientX - $t.offset().left);
		$('.ratingstars_average', $t).removeClass('ratingstar_grey')

		$('.indicator > span:first', $t)
			.show()
			.siblings()
				.hide()
				.text('');
	});
})(jQuery);
