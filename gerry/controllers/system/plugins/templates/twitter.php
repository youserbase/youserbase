<ul class="zebra">
<?php if (!empty($display_follow)): ?>
	<li class="first">
		<a href="http://twitter.com/<?=$username?>">
			<img src="<?=Assets::Image('twitter_follow.png')?>" alt="Follow <?=$username?>"/>
		</a>
	</li>
<?php endif; ?>
<?php foreach ($tweets as $index=>$tweet): ?>
	<li class="r<?=$index?> a<?=$index%2?>">
		<p class="content"><?=$tweet['content']?></p>
		<p class="time">
			<a href="<?=$tweet['link']?>">
				<?=twittertime($tweet['timestamp'])?>
			</a>
		</p>
	</li>
<?php endforeach; ?>
</ul>