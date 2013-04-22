<?php if (empty($news)): ?>
	<phrase id="NO_NEWS"/>
<?php else: ?>
<ul class="blog">
<?php foreach ((array)$news as $index=>$item): ?>
	<li class="r<?=$index?> a<?=$index%2?>">
		<a href="<?=FrontController::GetLink('News', 'News', 'Read', array('news_id'=>$item['entry_id']))?>" title="<?=BoxBoy::Prepare($item['title'])?> (<phrase id="VIA" quiet="true"/> <?=$feeds[$item['feed_id']]['title']?>)" class="lightbox more" rel="nofollow">
			<?=BoxBoy::Prepare($item['title'])?>
		</a>
		<span class="timestamp"><?=twittertime($item['timestamp'])?></span>
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
