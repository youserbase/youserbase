/*
 * Tim Gebken
 * 04.09.2009
 * 
 * EventClassen zum abfangen von Standard-Events
 * 

*/

$('.BACK').livequery('click', function(){
	history.back(1);
	return false;
});
$('.DELETE').livequery('click', function(){
	var message = $(this).metadata().message;
	if(typeof(message)=='undefined')message = 'DELETING OK?';
	return confirm(message);
	return false;
});
$('.CONFIRM').livequery('click', function(event){
	var message = $(this).metadata().message;
	if(typeof(message)=='undefined')message = 'OK?';
	return confirm(message);
});
$('.ALERT:not(select, option)').livequery('click', function(event){
	var message = $(this).metadata().message;
	var returning = $(this).metadata().returning;
	if(typeof(message)=='undefined')message = 'OK!';
	if(typeof(returning)=='undefined')returning = false;
	alert(message);
	return returning;
});
$('select.ALERT').livequery('change', function(event){
	$.log($(this).val());
	if (!$('option[value="' + $(this).val() + '"]', this).hasClass('ALERT')) {
		$('.indication.none').slideUp('fast');
		return;
	}
	var message = $(this).metadata().message;
	var returning = $(this).metadata().returning;
	if(typeof(message)=='undefined')message = 'OK!';
	if(typeof(returning)=='undefined')returning = false;
	alert(message);
	$('.indication.none').slideDown('slow');
	return returning;
});
$('.BLANK').livequery('click', function(event){
	$.getlocations($(this).attr('href'), '_blank');
	return false;
});
$('.SCRIPT_LINK').livequery('click', function(event){
	$.getlocations($(this).metadata().link);
	return false;
});
$('.OPEN_WINDOW').livequery('click', function(event){
	var width = $(this).metadata().width;
	var height = $(this).metadata().height;
	if(typeof(width)=='undefined')width = screen.width*0.6;
	if(typeof(height)=='undefined')height = screen.height*0.6;
	$.getlocations($(this).attr('href'), '_blank', 'width='+width+', height='+height+', scrollbars=yes');
	return false;
});
$('.CLOSE').livequery('click', function(event){
	var DOM = $(this).metadata().DOM;
	$(DOM).hide();
	return false;
});
$('.FILE_CHOOSEN').livequery('click', function(event){
	var DOM = $(this).metadata().DOM;
	var $DOM = $(DOM);
	if($DOM.val()==""){
		$DOM.parent().addClass('inputhint');
		alert($(this).metadata().message);
		return false;
	}
	return true;
});
$('.CLICK_INDICATOR').livequery('click', function(){
	$(this).addClass('indicator');
	return true;
});