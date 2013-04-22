<p>
<?php $i=0; foreach ($data as $item): ?>
	<?php if ($i++>0): ?>
	|
	<?php endif; ?>
	<?php if ($item['type']=='page'): ?>
		<a href="<?=FrontController::GetLink('System', 'Pages', 'Display', array('page'=>$item['link']))?>">
			<phrase id="BULK_<?=$section_name?>_<?=$item['title']?>"/>
		</a>
	<?php elseif ($item['external']): ?>
	<a href="<?=$item['link']?>" class="external"><?=$item['title']?></a>
	<?php else: ?>
	<a href="<?=$item['link']?>"><phrase id="<?=$item['title']?>"/></a>
	<?php endif; ?>
<?php endforeach; ?>
</p>