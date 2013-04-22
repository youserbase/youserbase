<ul class="zebra">
<?php foreach ($tweets as $index => $tweet): ?>
	<li class="r<?=$index?> a<?=$index % 2?>">
		<p class="content">
			<twitterfy:<?=$random_id?>><?=BoxBoy::Prepare($tweet['content'])?></twitterfy:<?=$random_id?>>
		</p>
		<p class="time">
			<a href="<?=$tweet['link']?>"><?=twittertime($tweet['timestamp'])?></a>
		</p>
	</li>
<?php endforeach; ?>
</ul>