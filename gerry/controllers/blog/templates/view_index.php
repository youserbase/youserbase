<?php foreach ($rss['items'] as $entry):?>
	<div class="rbox grey">
		<h3><?=strip_tags($entry['title'])?></h3>
		<?=$entry['content']?>
	</div>
<?php endforeach;?>