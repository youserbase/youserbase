<ul class="blog">
<?php foreach ($items as $index => $item): ?>
	<li class="a<?=$index%2?> r<?=$index?>">
		<a href="<?=$item['link']?>" class="external"><?=$item['title']?></a>
		<span class="timestamp"><?=twittertime($item['timestamp'])?></span>
	</li>
<?php endforeach; ?>
</ul>