$('ul#activity_list li .more').live('click', function () {
	$(this).toggleClass('open').closest('li').find('blockquote').slideToggle('normal');
});
$('ul#activity_list li blockquote').hide();