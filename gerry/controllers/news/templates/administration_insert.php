<form action="<?=FrontController::GetLink()?>" method="post" class="validate">
<dl>
	<dt>
		<label for="feed_url"> <phrase id="FEED_URL"/> </label>
	</dt>
	<dd>
		<input id="feed_url" name="url" type="text" class="required" value="<?=empty($_POST['url'])?'':$_POST['url']?>"/>
	</dd>
	<dt>&nbsp;</dt>
	<dd>
		<button type="submit"> <span> <phrase id="INSERT"/> </span> </button>
	</dd>
</dl>
</form>