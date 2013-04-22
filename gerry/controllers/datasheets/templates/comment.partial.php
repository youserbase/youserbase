<?php if ($offensive_counts <= $burn): ?>
<div class="rbox comment" id="<?=$comments_id?>comment">
	<h3>
		<img src="<?=Assets::Image('flags/'.$language.'.png')?>" alt_phrase="LANGUAGE_<?=$language?>" title_phrase="LANGUAGE_<?=$language?>" /> <?= dateformat($timestamp)?>
		|
		<strong>
		<?php if(is_numeric($youser_id) && $youser_id != 107):?>
			<youser id="<?=$youser_id?>"/>
		<?php else:?>
			<youser id="<?=$youser_id?>"/>: <?=htmlspecialchars($guest_name);?>
		<?php endif;?>
		</strong><!--<phrase id="<?=$category?>"/> -->
		<?=numberformat($positive)?>
		<a class="rate_comment stored:device_id:<?=$device_id?>" href="<?=FrontController::GetLink('ranking', array('comments_id' => $comments_id, 'device_id' => $device_id, 'like' => 1, 'return_to' => FrontController::GetURL()))?>" rel="nofollow">
			<img src="<?=Assets::Image('famfamfam/thumb_up.png')?>" alt_phrase="LIKE" title_phrase="LIKE"/>
		</a>
		<?=numberformat($negative)?>
		<a class="rate_comment stored:device_id:<?=$device_id?>" href="<?=FrontController::GetLink('ranking', array('comments_id' => $comments_id, 'device_id' => $device_id, 'dislike' => 1, 'return_to' =>FrontController::GetURL()))?>" rel="nofollow">
			<img src="<?=Assets::Image('famfamfam/thumb_down.png')?>" alt_phrase="dislike" title_phrase="dislike"/>
		</a>
		<phrase id="<?=strtoupper($category)?>"/>
	</h3>
	<p class="<?=$comments_id?>comment">
		<?=$comment?>
	</p>
	<div class="rfooter">
	
		<a class="translatecomment fleft stored:comments_id:<?=$comments_id?>" href="<?=FrontController::GetLink('Translate', array('comments_id' => $comments_id, 'comment' => $comment))?>"><phrase id="TRANSLATE" quiet="true/></a>
	
	<?php if (Youser::Id() == $youser_id || Youser::Is('root', 'administrator', 'god')): ?>
		<a class="lightbox button mini edit" href="<?=FrontController::GetLink('Edit', array('device_id' => $device_id, 'comments_id' => $comments_id, 'category' => $category, 'return_to' => FrontController::GetURL(), 'compare' => $type))?>">
			<span><phrase id="EDIT" quiet="true"/></span>
		</a>
	<?php endif; ?>
		<a class="burn button mini bell_add" href="<?=FrontController::GetLink('Burn', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' => FrontController::GetURL()))?>" rel="nofollow">
			<span><phrase id="NOTIFY"/></span>
		</a>
	<?php if (Youser::Is('administrator', 'root', 'god')): ?>
		<?=$offensive_counts?> <phrase id="WARNINGS"/>
		<a class="burn button mini bell_delete" href="<?=FrontController::GetLink('Unburn', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' =>FrontController::GetURL()))?>">
			<span><phrase id="UNNOTIFY"/></span>
		</a>
		<a class="burn button mini bin" href="<?=FrontController::GetLink('Delete', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' => FrontController::GetURL()))?>">
			<span><phrase id="DELETE"/></span>
		</a>
	<?php endif; ?>
	</div>
</div>
<?php elseif ($offensive_counts >= $burn && Youser::Is('administrator', 'root', 'god')): ?>
<div class="rbox comment" style="background-color:red;">
	<h3>
		<?= dateformat($timestamp)?> |   <strong><youser id="<?=$youser_id?>"/></strong><phrase id="<?=$category?>"/>
	</h3>
	<p>
		<?=$comment?>
	</p>
	<div class="rfooter">
		<a class="lightbox button mini edit" href="<?=FrontController::GetLink('Edit', compact('device_id', 'comments_id', 'category'))?>">
			<span><phrase id="EDIT"/></span>
		</a>
		<a class="burn button mini bell_delete" href="<?=FrontController::GetLink('Unburn', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' => FrontController::GetURL()))?>">
			<span><phrase id="UNNOTIFY"/></span>
		</a>
		<a class="burn button mini delete" href="<?=FrontController::GetLink('Delete', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' => FrontController::GetURL()))?>">
			<span><phrase id="DELETE"/></span>
		</a>
	</div>
</div>
<?php endif; ?>