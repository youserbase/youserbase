$(document).ready(function()
  {
  	$(".all_components").draggable(
  	{
  		helper: 'clone'
  	});
	$(".components").droppable(
	{
		accept: ".all_components",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) 
		{
			$(this).append('<option selected="selected" value='+ui.draggable.html()+'>'+ui.draggable.html()+'</option>');
		}
	});
	$(".all_functions").draggable(
  	{
  		helper: 'clone'
  	});
	$(".components").droppable(
	{
		accept: ".all_functions",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) 
		{
			$(this).append('<option selected="selected" value='+ui.draggable.html()+'>'+ui.draggable.html()+'</option>');
		}
	});
	$(".all_equipment").draggable(
  	{
  		helper: 'clone'
  	});
	$(".components").droppable(
	{
		accept: ".all_equipment",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) 
		{
			$(this).append('<option selected="selected" value='+ui.draggable.html()+'>'+ui.draggable.html()+'</option>');
		}
	});
});