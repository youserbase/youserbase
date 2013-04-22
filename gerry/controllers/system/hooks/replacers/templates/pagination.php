<?php
	$last_page = ceil($total/$max)-1;
	$pages = array(0, $last_page);
	for ($i=max(0, $current_page-1); $i<=min($last_page, $current_page+1); $i++)
	{
		array_push($pages, $i);
	}
	$pages = array_unique($pages);
	sort($pages);
?>
<div class="pagination <?=!empty($class)?$class:''?>">
<?php if ($current_page==0): ?>
	<span class="previous inactive">
		&lt; <phrase id="PAGINATION_FIRST" quiet="true"/>
	</span>
<?php else: ?>
	<span class="previous">
			<a href="<?=$href?><?=$current_page-1?>"<?=!empty($link_class)?' class="'.$link_class.'"':''?>>
				<img src="<?=Assets::Image('famfamfam/resultset_previous.png')?>" alt_phrase="PAGINATION_PREVIOUS" title_phrase="PAGINATION_PREVIOUS"/>
			</a>
	</span>
	<?php endif; ?>
<?php for ($page=reset($pages)-1; $old_page=$page, ($page=array_shift($pages))!==null;): ?>
	<?php if ($old_page!=$page-1): ?>
	<span class="divider" rel="<?=$old_page+1?>-<?=$page-1?>-<?=isset($link_class)?$link_class:''?>-<?=$href?>">&hellip;</span>
	<?php endif; ?>
	<span <?=$page==$current_page?'class="current"':''?>>
		<a href="<?=$href?><?=$page?>"<?=!empty($link_class)?' class="'.$link_class.'"':''?>><?=$page+1?></a>
	</span>
<?php endfor; ?>
<?php if ($current_page==$last_page): ?>
	<span class="next inactive"><phrase id="PAGINATION_NEXT" quiet="true"/> &gt;</span>
<?php else: ?>
	<span class="next">
		<a href="<?=$href?><?=$current_page+1?>"<?=!empty($link_class)?' class="'.$link_class.'"':''?>>
			<img src="<?=Assets::Image('famfamfam/resultset_next.png')?>" alt_phrase="PAGINATION_NEXT" title_phrase="PAGINATION_NEXT"/>
		</a>
	</span>
<?php endif; ?>
</div>