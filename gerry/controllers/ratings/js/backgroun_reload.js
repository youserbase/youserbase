$('.rating_box').live('click', function()
{
	var var_device_id = $("input[name='device_id']").val();
	var var_tab = $("input[name='sheet_tab']").val();
	var var_link = $("input[name='link']").val();
	$.get(var_link, {device_id: var_device_id, tab: var_tab},
	function(data)
	{
		$('div .phonesheet').replaceWith(data);
	});
});

$('.rating_help').live('click', function(){
	$('.help').toggle();
});