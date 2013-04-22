/*var GLOBALS = {
 URLS : '/login/'
 }
 */
;(function($){
    var w = 200,
    	indicator = [BabelFish('RATING_0'), BabelFish('RATING_1'), BabelFish('RATING_2'), BabelFish('RATING_3'), BabelFish('RATING_4'), BabelFish('RATING_5')],
    	isdone = BabelFish('IS_DONE'),
    	disable = BabelFish('DISABLED'),
		scoreAverage = "";

    $(".ratingbox:not(.disable, .isdone)").livequery("mousemove", function(event){
        var $t = $(this),
        	width = $t.hasClass('ratingbox_small') ? 50 : 100,
			offset = $t.hasClass('ratingbox_small') ? 26 : 50,
        	x = event.clientX - $t.offset().left,
        	//the score (range from 0.0 to 10.0 (int))
			//washing the x-wert
			//mit 0 im dezimalWert damit die zahl im Display nicht springt
        	score = Math.round(Math.min(width, Math.max(0, x-offset)) / (width/10)),
			scoreDisplay = (score == Math.round(score) && score!= 10) ? score + '.0' : score;
		//console.log(scoreDisplay);
		$('.indicator > span:first', $t)
        	.hide()
        	.siblings()
        	.show()
        	.text(indicator[Math.round(score/2)]);
        $('.rating_user .index', $t).width(x);
        $('.score', $t).text(scoreDisplay);
    });

    $(".ratingbox:not(.disable, .isdone)").livequery("mouseenter", function(event){
        var $t = $(this);
        $('.rating_user', $t).show();
		scoreAverage = $('.score', $t).text();
    });
    $(".ratingbox.isdone").livequery("mouseenter", function(event){
        var $t = $(this);
		$('.indicator > span:first', $t)
        	.hide()
        	.siblings()
        	.show()
        	.text(isdone);
		scoreAverage = $('.score', $t).text();
    });
    $(".ratingbox:not(.disable)").livequery("mouseleave", function(event){
        var $t = $(this);
        $('.rating_user', $t).hide();
		$('.indicator > span:first', $t)
			.show()
        	.siblings()
        	.hide();
		$('.score', $t).text(scoreAverage);
    });
	$(".ratingbox:not(.disable, .isdone)").livequery("click", function(event){
		var $t = $(this),
			rating = $('.score', $t).text();
        $('.rating_user', $t).hide();
        $('.rating_user', $t).hide();
		$('.indicator > span:first', $t)
			.show()
			.siblings()
			.hide();
		$('.score', $t).text(scoreAverage);
		$('.ratingbox_canvas, .score', $t).toggle();
		$('.ajax_indicator', $t).toggle();
		$.get('Ratings/Display/Save?device_id='+$t.retrieve('device_id')+'&table='+$t.retrieve('table')+'&tab='+$t.retrieve('tab')+'&rating='+rating+'&sheet_tab='+$t.retrieve('sheet_tab'));
		$('.phonesheet').load('datasheets/datasheets/phonesheet?device_id='+$t.retrieve('device_id')+'&tab='+$t.retrieve('sheet_tab'));
	});
})(jQuery);
