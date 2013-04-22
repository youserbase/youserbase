<?php if (!empty($tags)): ?>
	<p class="tag_cloud">
	<?php foreach ($tags as $tag=>$data): ?>
		<span class="tag">
			<a class="s<?=$data['class']?>" href="<?=FrontController::GetLink('Devices', 'Tags', 'Tag', array('tag'=>$tag))?>"><?=BoxBoy::Prepare($tag)?></a>
		</span>
	<?php endforeach; ?>
	</p>
<?php endif; ?>