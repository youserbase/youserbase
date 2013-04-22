<div class="<?=strtolower($section_name)?>">
	<h1><phrase id="NAVIGATION_<?=$section_name?>"/></h1>
	<div class="content">
		<ul>
		<?php foreach ($data as $item): ?>
			<li>
			<?php if (isset($item['plugin_content'])): ?>
				<?=$item['plugin_content']?>
			<?php elseif ($item['type']=='link'): ?>
				<a href="<?=$item['link']?>" class="external"><?=$item['title']?></a>
			<?php else: ?>
				<a href="<?=$item['link']?>"><phrase id="NAVIGATION_<?=$item['title']?>"/></a>
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>