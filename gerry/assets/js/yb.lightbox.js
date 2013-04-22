jQuery(function($) {
	var shadowbox_visible = false;
	function update_lightbox(content) {
		// Remove all script-tags to avoid js errors, borrowed from Prototype's stripScripts()
		var images = 0,
			iterations = 50,
			random_id = 'lb_'+(new Date()).getTime(),
			scripts = [],
			clean_content = content.replace(new RegExp('<script[^>]*>([\\S\\s]*?)<\/script>', 'img'), function(match) {
				scripts.push(match);
				return '';
			}),
			tmp = $('<div id="'+random_id+'" style="position: absolute; left: -5000px; top: -5000px;"/>')
				.html(clean_content)
				.appendTo('body');

		$('img', tmp).each(function () {
			images += 1;
			var image = new Image;
			image.onload = image.onerror = function () {
				images -= 1;
			};
			image.src = $(this).attr('src');
		});

		(function () {
			if (images && iterations--) {
				setTimeout(arguments.callee, 10);
			} else {
				Shadowbox.open({
					player : 'html',
					title : $(this).attr('title') || '',
					content : content,
					width : Math.max(500, tmp.outerWidth(true) + 50),
					height : Math.max(300, tmp.outerHeight(true) + 50)
				});
				tmp.remove();
				Shadowbox.applyOptions({
					onFinish : function () {
						var script;
						Youserbase.consume();
						while (script = scripts.shift()) {
							$(script).appendTo($('head'));
						}
					}
				});
			};
		})();
	};

	Shadowbox.LANG = {
	    code:       GLOBALS.Language,
	    of:         BabelFish('SHADOWBOX_OF'),
	    loading:    BabelFish('SHADOWBOX_LOADING'),
	    cancel:     BabelFish('SHADOWBOX_CANCEL'),
	    next:       BabelFish('SHADOWBOX_NEXT'),
	    previous:   BabelFish('SHADOWBOX_PREVIOUS'),
	    play:       BabelFish('SHADOWBOX_PLAY'),
	    pause:      BabelFish('SHADOWBOX_PAUSE'),
	    close:      BabelFish('SHADOWBOX_CLOSE'),
	    errors: {
	        single: BabelFish('SHADOWBOX_ERROR_SINGLE'),
	        shared: BabelFish('SHADOWBOX_ERROR_SHARED'),
	        either: BabelFish('SHADOWBOX_ERROR_EITHER')
	    }
	};

	var last_title = null;
	// Init Shadowbox
	Shadowbox.init({
		skipSetup : true,
//		animate : false,
		animSequence: 'sync',
		enableKeys : false,
		onOpen : function () {
			last_title = last_title||document.title;
			$(document).trigger('change_title');
			shadowbox_visible = true;
		},
		onClose : function () {
			if (last_title) {
				Youserbase.CurrentTitle = last_title;
			}
			$(document).trigger('change_title');
			last_title = null;
			shadowbox_visible = false;
		}
	});

	// Open lightbox on all clicks on a.lightbox that are not .close
	$('a.lightbox:not(.close)').live('click', function (event) {
		if ((event.button != ($.browser.msie ? 1 : 0)) || event.metaKey || event.shiftKey || event.altKey) {
			return;
		}
		$.get($(this).attr('href'), update_lightbox);
		return false;
	});

	// Close lightbox on all clicks on a.lightbox.close
	$('.lightbox.close').live('click', function () {
		Shadowbox.close();
		return false;
	});

	// AJAX event to close the lightbox if desired
	$('body').ajaxComplete(function (event, transport) {
		if (!transport) {
			return;
		};
		if (transport.getResponseHeader('X-Close-Lightbox')) {
			Shadowbox.close();
		};
	});

	$(document).bind('submitted.lightbox', function (event, response) {
		if (shadowbox_visible && response.length) {
			update_lightbox(response);
		};
	}).keydown(function (event) {
		if (shadowbox_visible && event.keyCode==27) {
			Shadowbox.close();
			return false;
		};
	});
});