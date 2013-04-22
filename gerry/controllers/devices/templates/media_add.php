<form action="<?=FrontController::GetLink(compact('device_id'))?>" method="post" class="yform columnar lightbox">
	<fieldset>
		<legend><phrase id="ADD_MEDIA" /></legend>

		<div class="type-text">
			<label for="url"><phrase id="URL" /></label>
			<input type="text" name="url" id="url" value="<?=@$_POST['url']?>" />
		</div>

		<div class="type-text">
			<label for="title"><phrase id="TITLE" /></label>
			<input type="text" name="title" id="title" value="<?=@$_POST['title']?>" />
		</div>

		<div class="type-text">
			<label for="author"><phrase id="AUTHOR" /></label>
			<input type="text" name="author" id="author" value="<?=@$_POST['author']?>" />
		</div>

		<div class="type-button">
			<span> <button class="add"><phrase id="ADD" /></button> </span>
		</div>
	</fieldset>
</form>

<script type="text/javascript">
//<![CDATA[
$('#url').change(function() {
	var value = $(this).val(),
		media_key = '';
	if (value.match(/youtube\.com/)) {
		media_key = value.replace(/^.*\?v=(.*?)(?:&.*)?$/, '$1');

		$.getJSON("http://query.yahooapis.com/v1/public/yql?q=select%20published%2C%20title.content%2C%20author.name%20from%20xml%20where%20url%3D%22http%3A%2F%2Fgdata.youtube.com%2Ffeeds%2Fbase%2Fvideos%2F"+media_key+"%22&format=json&diagnostics=false&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=?", function(json) {
			$('#title').val(json.query.results.entry.title);
			$('#author').val(json.query.results.entry.author.name);
		});
	}
});
//]]>
</script>