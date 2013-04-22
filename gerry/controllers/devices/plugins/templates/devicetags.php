<div class="content">
<?php if (!empty($tags)): ?>
	<p class="tag_cloud">
	<?php foreach ($tags as $tag=>$data): ?>
		<?php if (!Youser::Id()): ?>
		<span class="tag">
		<?php elseif (isset($data['yoused'])): ?>
		<span class="tag tagged">
			<a class="remove ajax  target:closest:.content" href="<?=FrontController::GetLink('Plugin', 'DeviceTags', 'RemoveTag', array('return_to'=>FrontController::GetURL(), 'device_id'=>$device_id, 'tag'=>$tag))?>">
				<img src="<?=Assets::Image('famfamfam/bullet_delete.png')?>" alt_phrase="DEVICETAGS_REMOVE"/>
			</a>
		<?php else: ?>
		<span class="tag">
			<a class="add ajax target:closest:.content" href="<?=FrontController::GetLink('Plugin', 'DeviceTags', 'AddTag', array('return_to'=>FrontController::GetURL(), 'device_id'=>$device_id, 'tag'=>$tag))?>">
				<img src="<?=Assets::Image('famfamfam/bullet_add.png')?>" alt_phrase="DEVICETAGS_ADD"/>
			</a>
		<?php endif; ?>
			<a class="s<?=$data['class']?>" href="<?=FrontController::GetLink('Devices', 'Tags', 'Tag', array('tag'=>$tag))?>"><?=BoxBoy::Prepare($tag)?></a>
		</span>
	<?php endforeach; ?>
	</p>
<?php endif; ?>

<?php if (Youser::Id()): ?>
	<form action="<?=FrontController::GetLink('Plugin', 'DeviceTags', 'AddTag', array('return_to'=>FrontController::GetURL()))?>" method="post" class="ajax target:closest:.content">
		<p class="right">
			<label for="tag"><phrase id="DEVICETAGS_TAG"/></label>
			<input id="tag" name="tag" class="short" value=""/>
			<br />
			<button type="submit" class="add"> <span><phrase id="DEVICETAGS_ADD"/></span> </button>
		</p>
		<p class="small right">
			<phrase id="DEVICETAGS_NOTICE"/>
			<input type="hidden" name="device_id" value="<?=$device_id?>"/>
		</p>
	</form>
<?php endif; ?>

<script type="text/javascript">
//<![CDATA[
$('.tag_cloud .tag').draggable({
	helper: 'clone',
	revert: true,
	delay: 300
});
$('form #tag').bind('adjust', function() {
	var values = [];
	jQuery.each(jQuery.makeArray($(this).val().replace(',', ' ').split(' ')), function(key, value) {
		if (value.length && jQuery.inArray(value, values)<0) {
			values.push(value);
		}
	});
	$(this).val(values.join(' '));
}).droppable({
	accept: '.tag:not(.tagged)',
	activeClass: 'active',
	drop: function(e, ui) {
		$(this).val( $(this).val() + ' ' + $('a:last', ui.draggable).text() ).trigger('adjust');
	}
});
$('.tag_cloud .tag a.add').click(function() {
	$('form #tag').val( $('form #tag').val() + ' ' + $(this).next().text() ).trigger('adjust');
	return false;
});
//]]>
</script>
</div>
