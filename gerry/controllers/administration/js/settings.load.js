(function () {

	var timeout = null,
		last_value = null;
	$('#dc_meta_description, #dc_meta_keywords').live('keyup', function () {
		var $self = $(this),
			value = $self.val().toUpperCase().replace(/[^a-zA-Z0-9]/, '_');
		if (value == $self.data('value')) {
			return;
		}

		$self.val(value);

		if ($self.data('timeout')) {
			clearTimeout($self.data('timeout'));
		}
		$self.data('timeout', setTimeout(function () {
			$self.siblings('.type-check').load(GLOBALS.URLS['Babelfish-Tell'], {
				language : GLOBALS.Language,
				phrase_id : value
			}, function () {
				$self.data('value', value);
			});
		}, 300));
	});

})();
