<?php if (!empty($link)):?>
	<?php $link = sprintf_ready($link)?>
<a href="<?=sprintf($link, 0)?>" class="first <?=($index==0)?'disabled':''?>">
	<img src="<?=Assets::Image('famfamfam/control_start.png')?>" alt="&laquo;" title_phrase="FIRST_<?=$phrase?>" />
</a>
<a href="<?=sprintf($link, max($index-1, 0))?>" class="previous <?=($index==0)?'disabled':''?>">
	<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="&lt;" title_phrase="PREVIOUS_<?=$phrase?>" />
</a>
<a href="<?=sprintf($link, min($index+1, $max))?>" class="next <?=($index<$max)?'':'disabled'?>">
	<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="&gt;" title_phrase="NEXT_<?=$phrase?>" />
</a>
<a href="<?=sprintf($link, $max)?>" class="last <?=($index<$max)?'':'disabled'?>">
	<img src="<?=Assets::Image('famfamfam/control_end.png')?>" alt="&raquo;" title_phrase="LAST_<?=$phrase?>" />
</a>
<?php endif;?>