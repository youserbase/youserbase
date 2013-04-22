<div class="comments">
	<div class="comments_interface">
		<h4><phrase id="PAGE"/> <?=$page+1?> <phrase id="OF"/> <?=ceil($comment_count/$limit)?></h4>
		<div class="floatbox clearfix">
			<?=$this->render_partial('commentspagination', compact('device_id', 'page', 'comment_count', 'limit', 'skip', 'order_by'))?>
			<div class="fright">
				<a class="lightbox button add" rel="nofollow" href="<?=FrontController::GetLink('Edit', array('device_id' => $device_id, 'compare' => $compare, 'page' => $order_by=='ASC'?ceil($comment_count+1)/$limit-1:'0', 'return_to' => str_replace('/COMMENTS', '#COMMENTS', FrontController::GetURL())))?>">
					<span><phrase id="NEW_COMMENT"/></span>
				</a>
			</div>
		</div>
	</div>
	<div class="floatbox">
		<?=$this->render_partials('comment', (array)$comments, compact('burn'))?>
	</div>
	<?php if($comment_count > 25):?>
		<div class="floatbox clearfix">
			<?=$this->render_partial('commentspagination', compact('device_id', 'page', 'comment_count', 'limit', 'skip', 'order_by'))?>
			<div class="fright">
				<a class="lightbox button add" rel="nofollow" href="<?=FrontController::GetLink('Edit', array('device_id' => $device_id, 'compare' => $compare, 'page' => $order_by=='ASC'?ceil($comment_count+1)/$limit-1:'0', 'return_to' => str_replace('/COMMENTS', '#COMMENTS', FrontController::GetURL())))?>">
					<span><phrase id="NEW_COMMENT"/></span>
				</a>
			</div>
		</div>
	<?php endif;?>
</div>
<script type="text/javascript">
//<![CDATA[
if (!$('body').data('comment_handlers_attached')) {
	$('.update').livequery('change', function(){
		var value = '';
		$('input.update').each(function(){
			var name = $(this).attr('name');
			value += $(this).attr('checked')
				? '&' + name + '=' + $(this).attr('value')
				: '&' + name + '=null';
		});
		$('.comments').load('datasheets/Datasheets_Comments/Index?device_id=<?=$device_id?>&' + value);
		return false;
	});

	$('.burn').live('click', function(){
		var target = $(this).attr('href'),
			value = '';
		$('input.update').each(function(){
			var name = $(this).attr('name');
			value += $(this).attr('checked')
				? '&' + name + '=' + $(this).attr('value')
				: '&' + name + '=null';
		});
		$.post(target, function() {
			$('.comments').load('datasheets/Datasheets_Comments/Index?device_id=<?=$device_id?>&' + value);
		});

		return false;
	});

	$('body').data('comment_handlers_attached', true);
}
//]]>
</script>
