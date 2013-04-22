$('form.ajax.edit_component').live('change', function(){
	if($('form.ajax.edit_component').hasClass('changed'))
	{
		return true;
	}
	else
	{
		$('form.ajax.edit_component').addClass('changed');
		$('form.ajax.edit_component').append('<input type="hidden" name="changed" value="true"/>');
	}
});

$('form.ajax.new_comment').livequery('submitted', function(){
	$('div.type-button.savecomment').remove();
	Shadowbox.close();
	var device_id = $(this).retrieve('device_id');
	var page = $(this).retrieve('page');
	setTimeout(function() {
		$('.comments').load('datasheets/Datasheets_Comments/Index?device_id='+device_id+'&page='+page);
	}, 100);
});

$('form.ajax.new_comment .comment').livequery('keyup', function(){
	var maxlength = 500;
	var text = $(this).val();
	var used = text.length;
	var rest = maxlength-used;
	if (used >= maxlength)
	{
		$(this).val(text.substr(0, maxlength));
		$('.infodiv').replaceWith('<div class="infodiv">NO_CHARACTERS_LEFT</div>');
		$('.infodiv').animate({ backgroundColor: "red" }, 50);
		return false;
	}
	else
	{
		$('.infodiv').html(maxlength-used);
		return true;
	}
});

$('form.ajax.comments_search .needle').livequery('keyup', function(){
	var needle = $(this).val();
	var device_id = $(this).retrieve('device_id');
	var needle = needle.replace(/ /g, "+");
	console.log(needle);
	setTimeout(function() {
	$('.comments').load('datasheets/Datasheets_Comments/Index?device_id='+device_id+'&needle='+needle);
	}, 200);
});

$('.limit_comments').livequery('submit', function(){
	var device_id = $(this).retrieve('device_id');
	setTimeout(function() {
		$('.comments').load('datasheets/Datasheets_Comments/Index?device_id='+device_id);
	}, 200);
});

 $('.comments_pagination').livequery('click', function(){
    	var target = $(this).attr('href');
    	var device_id = $(this).retrieve('device_id');
    	var page = $(this).retrieve('page');
    	$('.comments').load(target+'?device_id='+device_id+'&page='+page);
    	return false;
});

$('.comments_orderby').livequery('click', function(){
	var target = $(this).attr('href');
	var device_id = $(this).retrieve('device_id');
	var order_by = $(this).retrieve('order_by');
	$('.comments').load(target+'?device_id='+device_id+'&order_by='+order_by);
	return false;
});

$('.translatecomment').livequery('click', function(){
	var target = $(this).attr('href');
	var comment_id = $(this).retrieve('comments_id');
	var comment = $(this).retrieve('comment');
	$('.'+comment_id+'comment').load(target);
	
	return false;
});

/*
$('.rate_comment').livequery('click', function(){
	var device_id = $(this).retrieve('device_id');
	var comments_id = $(this).retrieve('comments_id');
	var like = $(this).retrieve('like');
	var dislike = $(this).retrieve('dislike');
	if(like == 1)
	{
		$.post('datasheets/Datasheets_Comments/Ranking?comments_id='+comments_id+'&like='+like);
	}
	else if(dislike == 1)
	{
		$.post('datasheets/Datasheets_Comments/Ranking?comments_id='+comments_id+'&dislike='+dislike);
	}
	setTimeout(function() {
		$('.comments').load();
	}, 200);
	return false;
});
*/
$('.rate_comment').live('click', function() {
	var device_id = $(this).retrieve('device_id');
	$.post($(this).attr('href'), function() {
		$('.comments').load('datasheets/Datasheets_Comments/Index?device_id='+device_id);
	});
	return false;
});

$('.cancel').live('click', function () {
	Shadowbox.close();
});

$('.versioning').live('click', function() {
	var src = $(this).attr('id');
	var replace = $(this).attr('title');
	$('.'+replace).fadeOut('slow');
	$('.'+replace)
		.load(src);
		$('.'+replace).fadeIn('slow');
});

$('.add_comment').live('click', function() {
	var tog = $(this).attr('id');
	$('div .'+tog).toggle();
});

$('.edit_link').live('click', function() {
	var tog = $(this).attr('id');
	$('div .'+tog).toggle();
});

$('.offensive_comment').live('click', function() {
	var tog = $(this).attr('id');
	$('.'+tog).toggle();
});



$('.device_rating_extended').live('click', function() {
	$('.device_rating_extended').toggle();
});

$('.add_lang').live('click', function() {
	$('.lang_form').toggle();
});

$('.feature_rating_extended').live('click', function() {
	$(this).parents('tr').find('.feature_rating_extended').toggle();
});

$('.phonesheet .edit').live('click', function() {
	$(this).closest('tbody')
		.toggleClass('editing')
		.toggleClass('hoverable');
});

$('.phonesheet .disclaimer').live('click', function() {
	$('.disclaimer_edit').toggle();
	$('.disclaimer_show').toggle();
});

$('.phonesheet select').live('change', function() {
	$("select[name='"+$(this).attr('name')+"']:visible").val($(this).val());
});

$('.select_category').live('change', function() {
	var visible = $('.select_category').val();
	$('.comment').hide();
	$('.'+visible).show();
});

$('.phonesheet button.cancel').live('click', function() {
	$(this).closest('.editing').removeClass('editing');
	return false;
});

$('.device_header .controls').each(function() {
	$self = $(this);
	jQuery.globalEval('var json = ' + $self.attr('rel'));

	$self.data('current', parseInt(json.current));
	$self.data('max', parseInt($('.last', $self).attr('href').replace(/^.*(\?|&)picture_id=/, '').replace(/(&.*)?$/, '')));
	$self.data('url', json.url);
	$self.data('target', json.target);
	var cache = [];
	cache[$self.data('current')] = $(json.target).attr('src');
	$self.data('cache', cache);

	$('.first', $self).click(function() {
		$self.trigger('load_image', 0);
		return false;
	});
	$('.previous', $self).click(function() {
		$self.trigger('load_image', Math.max($self.data('current')-1, 0));
		return false;
	});
	$('.next', $self).click(function() {
		$self.trigger('load_image', Math.min($self.data('current')+1, $self.data('max')));
		return false;
	});
	$('.last', $self).click(function() {
		$self.trigger('load_image', $self.data('max'));
		return false;
	});

	$( $self.data('target') ).bind('update', function(event) {
		var cache = $self.data('cache');

		$(this).attr('src', cache[$self.data('current')]);
		$(this).parent().attr('href', $(this).parent().attr('href').replace(/(picture_id|picture_index)=\d+/, 'picture_index='+$self.data('current')));
		($self.data('current')==0)
			? $('.first,.previous', $self).addClass('disabled')
			: $('.first,.previous', $self).removeClass('disabled') ;
		($self.data('current')==$self.data('max'))
			? $('.last,.next', $self).addClass('disabled')
			: $('.last,.next', $self).removeClass('disabled');
	});
}).bind('load_image', function(event, index) {
	var $self = $(this);
	if (index==$self.data('current') || index<0 || index>$self.data('max')) {
		return false;
	}
	$self.data('current', index);

	var cache = $self.data('cache');

	if (!cache[index]) {
		$.getJSON( $self.data('url').replace('%s', index), function(json) {
			$.each(json, function(index) {
				cache[index] = this;
			});
			$self.data('cache', cache);
			$( $self.data('target') ).trigger('update');
		});
	} else {
		$( $self.data('target') ).trigger('update');
	}
});
