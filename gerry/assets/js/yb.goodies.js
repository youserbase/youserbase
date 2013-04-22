$('.image.youtube').live('mouseover', function() {
	var $this = $(this);
	if (!$this.data('url'))
		$this.data('url', $this.attr('src'));
	if (!$this.data('running')) {
		$this.data('running', true);
		$this.data('index', -1);
		$this.data('start', (new Date().getTime()));
		$this.data('interval', setInterval(function() {
			var index = Math.floor(((new Date()).getTime() - $this.data('start'))/1000);
			if (index>$this.data('index')) {
				$this.attr('src', $this.data('url').replace(/default\.jpg$/, ((index%3)+1)+'.jpg'));
				$this.data('index', index);
			}
		}, 100));
	}
}).live('mouseout', function() {
	var $this = $(this);
	clearInterval($this.data('interval'));
	$this.data('running', false);
	$this.attr('src', $this.data('url'));
});