<div class="comments_control fleft">
	<a class="comments_pagination <?=$page>1?'':'disabled'?> stored:device_id:<?=$device_id?> stored:page:0" href="<?=FrontController::GetLink('Index')?>">
		<img src="<?=Assets::Image('famfamfam/control_start.png')?>" alt="first"/>
	</a>
	<a class="comments_pagination <?=$page>0?'':'disabled'?> stored:device_id:<?=$device_id?> stored:page:<?=$page>0?$page-1:$page?>" href="<?=FrontController::GetLink('Index')?>">
		<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/>
	</a>
	<a class="comments_pagination <?=$skip+$limit<=$comment_count?'':'disabled'?> stored:device_id:<?=$device_id?> stored:page:<?=$skip+$limit<=$comment_count?ceil($page+1):ceil($page)?>" href="<?=FrontController::GetLink('Index')?>">
		<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/>
	</a>
	<a class="comments_pagination <?=ceil($comment_count/$limit)>$page+1?'':'disabled'?> stored:device_id:<?=$device_id?> stored:page:<?=ceil($comment_count/$limit)>$page+1?ceil($comment_count/$limit)-1:$page?>" href="<?=FrontController::GetLink('Index')?>">
		<img src="<?=Assets::Image('famfamfam/control_end.png')?>" alt="last"/>
	</a>
	<phrase id="ORDER"/>
	<?php if($order_by == 'ASC'):?>
		<a class="comments_orderby stored:device_id:<?=$device_id?> stored:order_by:DESC" href="<?=FrontController::GetLink('Index')?>">
			<phrase id="NEWEST_FIRST"/> <span class="sort-icon asc">▼</span>
		</a>
	<?php elseif($order_by == 'DESC'):?>
		<a class="comments_orderby stored:device_id:<?=$device_id?> stored:order_by:ASC" href="<?=FrontController::GetLink('Index')?>">
			<phrase id="NEWEST_LAST"/> <span class="sort-icon desc">▲</span>
		</a>
	<?php endif;?>
	<form class="ajax limit_comments stored:device_id:<?=$device_id?>" method="post" action="<?=FrontController::GetLink('Limit')?>">
		<select name="limit">
			<option <?=$limit==25?'selected="selected"':''?>>25</option>
			<option <?=$limit==50?'selected="selected"':''?>>50</option>
			<option <?=$limit==75?'selected="selected"':''?>>75</option>
			<option <?=$limit==100?'selected="selected"':''?>>100</option>
		</select>
		<input type="hidden" name="device_id" value="<?=$device_id?>"/>
		<button><span><phrase id="UPDATE" quiet="true"/></span></button>
	</form>
</div>