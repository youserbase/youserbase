<ul class="zebra">
<?php if ($total>$limit): ?>
	<li class="first options">
	    <?php if ($skip > 0): ?>
	    <div class="fleft">
	        <a href="<?=FrontController::GetLink('Plugin', 'NewestComments', 'skip_comments', array('skip' => $skip-$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/></a>
	    </div>
	    <?php endif; ?>
	    <?php if ($skip + $limit < $total): ?>
	    <div class="fright">
	        <a href="<?=FrontController::GetLink('Plugin', 'NewestComments', 'skip_comments', array('skip' => $skip+$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/></a>
	    </div>
	    <?php endif; ?>
	    &nbsp;
	</li>
<?php endif; ?>
<?php foreach ($comments as $index=>$comment): ?>
	<li class="r<?=$index?> a<?=$index%2?>">
		<div class="header">
			<youser id="<?=$comment['youser_id']?>"/>
			&raquo;
			<?php if ($comment['type'] == 'device'):?>
				<device id="<?=$comment['device_id']?>" tab="Comments"/>
			<?php else:?>
				<a href="<?=FrontController::GetLink('datasheets', 'Compare', 'Index', array('compare_id' => $comment['device_id'], 'tab' => 'Comments'));?>"><phrase id="COMPARE"/></a>
			<?php endif;?>
		</div>
		<p class="content">
			<?=str_truncate(strip_tags($comment['comment']), $max_comment_length)?>
		</p>
		<p class="time"><?=twittertime($comment['timestamp'])?></p>
	</li>
<?php endforeach;?>
</ul>