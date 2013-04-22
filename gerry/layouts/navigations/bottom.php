<?php foreach ($data as $item): ?>
	<?php if ($item['external']): ?>
	<a href="<?=$item['link']?>" class="external"><?=$item['title']?></a>
	<?php else: ?>
	<a href="<?=$item['link']?>"><phrase id="BOTTOM_<?=$item['title']?>"/></a>
	<?php endif; ?>
<?php endforeach; ?>