<div class="sliding_nav">
	<ul class="tabify">
	<?php foreach ($tabs as $link=>$data): ?>
		<li>
			<a href="<?=is_numeric($link)?FrontController::GetLink(is_string($data)?$data:$data['name']):$link?>" title="<?=is_string($data)?$data:(isset($data['title'])?$data['title']:$data['name'])?>">
			<?php if (is_array($data) and isset($data['html'])): ?>
				<?=$data['html']?>
			<?php else: ?>
				<phrase id="<?=strtoupper(is_string($data)?'TAB_'.$data:(isset($data['phrase_id'])?$data['phrase_id']:$data['name']))?>"/>
			<?php endif; ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
