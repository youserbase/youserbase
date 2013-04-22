<div class="news article">
	<h1><?=$news['title']?></h1>
	<small>
		<?=dateformat($news['timestamp'])?>
	</small>
	<q cite="<?=$news['url']?>">
		<?=nl2br(trim($news['content']))?>
	</q>
	<p>
		<phrase id="VIA"/>
		<a href="<?=$news['url']?>">
			<img src="<?=$feed['image']?>" alt=""/>
			<?=$feed['title']?>
		</a>
	</p>
</div>
