$.debug(true);
function BabelFish(id){
    return ({
        RATING_0: 'gar nix',
        RATING_1: 'schlecht',
        RATING_2: 'naja',
        RATING_3: 'mittel',
        RATING_4: 'gut',
        RATING_5: 'bestens',
        IS_DONE: 'Du hast schon gewertet.',
        DISABLED: 'Du musst angemeldet sein, um bewerten zu können.'
    })[id] ||
    ('untranslated: ' + id);
}
/*var GLOBALS = {
 URLS : '/login/'
 }
 */
(function($){
    var w = 200;
    var indicator = [BabelFish('RATING_0'), BabelFish('RATING_1'), BabelFish('RATING_2'), BabelFish('RATING_3'), BabelFish('RATING_4'), BabelFish('RATING_5')];
    var isdone = BabelFish('IS_DONE');
    var disable = BabelFish('DISABLED');
	var scoreAverage = "";
    
    $(".ratingbox:not(.disable, .isdone)").livequery("mousemove", function(event){
        $t = $(this);
        var width = $t.hasClass('bigone') ? 100 : 100;
        var x = event.clientX - $t.offset().left;
        //the score (range from 0.0 to 10.0 (int))
		//washing the x-wert
		//mit 0 im dezimalWert damit die zahl im Display nicht springt
        var score = Math.min(100, Math.max(0, x-50)) / 10;
		scoreDisplay = score == Math.round(score) && score!= 10 ? score + '.0' : score;
		
		$('.indicator > span:first', $t)
         .hide()
         .siblings()
         .show()
         .text(indicator[Math.round(score/2)]);
        $('.rating_user .index', $t).width(x);
        $('.score', $t).text(scoreDisplay);
    });
    
    $(".ratingbox:not(.disable, .isdone)").livequery("mouseenter", function(event){
        $t = $(this);
        $('.rating_user', $t).show();
		scoreAverage = $('.score', $t).text();
    });
    $(".ratingbox.isdone").livequery("mouseenter", function(event){
        $t = $(this);
		$('.indicator > span:first', $t)
         .hide()
         .siblings()
         .show()
         .text(isdone);
		scoreAverage = $('.score', $t).text();
    });
    $(".ratingbox:not(.disable)").livequery("mouseleave", function(event){
        $t = $(this);
        $('.rating_user', $t).hide();
		$('.indicator > span:first', $t)
		 .show()
         .siblings()
         .hide();
		 $('.score', $t).text(scoreAverage);
    });
	$(".ratingbox:not(.disable, .isdone)").livequery("click", function(event){
		$t = $(this);
        $('.rating_user', $t).hide();
        $('.rating_user', $t).hide();
		$('.indicator > span:first', $t)
		 .show()
         .siblings()
         .hide();
		 $('.score', $t).text(scoreAverage);
		$('.ratingbox_canvas, .score', $t).toggle();
		$('.ajax_indicator', $t).toggle();
		setTimeout(function(){
			$('.ratingbox_canvas, .score', $t).toggle();
			$('.ajax_indicator', $t).toggle();
			$t.addClass('isdone');			
		}, 1000);
	});
})(jQuery);
